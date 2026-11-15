<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show user profile.
     */
    public function show(): View
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('profile.index', compact('user', 'orders'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'address' => $validated['address'] ? trim($validated['address']) : null,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
