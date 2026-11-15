<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display checkout page.
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $total = $cartItems->sum(function (CartItem $item) {
            return $item->subtotal;
        });

        return view('checkout.index', compact('cartItems', 'total', 'user'));
    }

    /**
     * Process checkout and create order.
     */
    public function process(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', 'in:Transfer Bank,COD,QRIS Simulasi'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Verify stock for all items
        foreach ($cartItems as $item) {
            if (! $item->product || ! $item->product->is_active) {
                return redirect()->route('cart.index')
                    ->with('error', 'Salah satu produk di keranjang sudah tidak aktif.');
            }
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok produk '{$item->product->name}' tidak mencukupi (sisa {$item->product->stock}).");
            }
        }

        $order = DB::transaction(function () use ($user, $validated, $cartItems) {
            $totalAmount = $cartItems->sum(fn ($item) => $item->subtotal);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                // Decrement stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            CartItem::where('user_id', $user->id)->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Checkout berhasil! Pesanan Anda telah diterima.');
    }

    /**
     * Show order receipt.
     */
    public function show(Order $order): View
    {
        // Enforce ownership check (anti-IDOR)
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan. Anda hanya dapat melihat pesanan Anda sendiri.');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
