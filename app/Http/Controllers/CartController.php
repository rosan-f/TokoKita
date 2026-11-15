<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the current user's shopping cart.
     */
    public function index(): View
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function (CartItem $item) {
            return $item->subtotal;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);

        $product = Product::active()->findOrFail($productId);

        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi untuk jumlah yang diminta.');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Jumlah total di keranjang melebihi stok yang tersedia.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang belanja.');
    }

    /**
     * Update an item's quantity in the cart.
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        // Enforce ownership check
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $quantity = (int) $validated['quantity'];

        if ($quantity <= 0) {
            $cartItem->delete();
            return back()->with('info', 'Produk dihapus dari keranjang.');
        }

        if ($cartItem->product->stock < $quantity) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia (' . $cartItem->product->stock . ' unit).');
        }

        $cartItem->update(['quantity' => $quantity]);

        return back()->with('success', 'Keranjang belanja berhasil diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(CartItem $cartItem): RedirectResponse
    {
        // Enforce ownership check
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $cartItem->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
