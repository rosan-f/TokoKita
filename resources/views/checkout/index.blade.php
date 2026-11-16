@extends('layouts.app')

@section('title', 'Checkout Pesanan - TokoKita')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Checkout Pesanan</h1>
    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi pengiriman dan metode pembayaran untuk menyelesaikan transaksi</p>
</div>

<form action="{{ route('checkout.process') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Shipping & Payment Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shipping Information Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-extrabold">1</span>
                    Informasi Pengiriman
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="shipping_name" class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap Penerima</label>
                        <input type="text" id="shipping_name" name="shipping_name" value="{{ old('shipping_name', $user->name) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm">
                    </div>

                    <div>
                        <label for="shipping_phone" class="block text-xs font-semibold text-slate-600 mb-1">Nomor Telepon / WhatsApp Aktif</label>
                        <input type="text" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone', $user->phone) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm">
                    </div>

                    <div>
                        <label for="shipping_address" class="block text-xs font-semibold text-slate-600 mb-1">Alamat Lengkap Pengiriman</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm">{{ old('shipping_address', $user->address) }}</textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-semibold text-slate-600 mb-1">Catatan Tambahan untuk Kurir (Opsional)</label>
                        <input type="text" id="notes" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Titip di pos satpam lab komputer"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm">
                    </div>
                </div>
            </div>

            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-extrabold">2</span>
                    Metode Pembayaran
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="border border-slate-200 rounded-2xl p-4 flex flex-col justify-between cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm text-slate-800">Transfer Bank</span>
                            <input type="radio" name="payment_method" value="Transfer Bank" checked class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <span class="text-xs text-slate-500">Virtual Account Lab Simulasi</span>
                    </label>

                    <label class="border border-slate-200 rounded-2xl p-4 flex flex-col justify-between cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm text-slate-800">QRIS Simulasi</span>
                            <input type="radio" name="payment_method" value="QRIS Simulasi" class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <span class="text-xs text-slate-500">Scan QR instan (Lab Test)</span>
                    </label>

                    <label class="border border-slate-200 rounded-2xl p-4 flex flex-col justify-between cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm text-slate-800">COD</span>
                            <input type="radio" name="payment_method" value="COD" class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <span class="text-xs text-slate-500">Bayar saat kurir tiba di lab</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-extrabold text-slate-900 mb-4">Item Pesanan ({{ $cartItems->count() }})</h2>

            <div class="space-y-3 max-h-60 overflow-y-auto pr-1 divide-y divide-slate-100">
                @foreach ($cartItems as $item)
                    <div class="pt-3 first:pt-0 flex justify-between text-xs">
                        <div class="pr-2">
                            <span class="font-bold text-slate-800 block">{{ $item->product->name }}</span>
                            <span class="text-slate-400">{{ $item->quantity }} x {{ $item->product->formatted_price }}</span>
                        </div>
                        <span class="font-extrabold text-slate-800 whitespace-nowrap">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            <div class="space-y-2 text-sm text-slate-600 py-4 my-4 border-y border-slate-100">
                <div class="flex justify-between text-xs">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span>Ongkos Kirim</span>
                    <span class="font-bold text-emerald-600">Rp 0 (Gratis Lab)</span>
                </div>
            </div>

            <div class="flex justify-between items-baseline mb-6">
                <span class="text-sm font-bold text-slate-900">Total Tagihan</span>
                <span class="text-2xl font-extrabold text-blue-600">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-center rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Konfirmasi & Buat Pesanan
            </button>

            <a href="{{ route('cart.index') }}" class="block text-center text-xs font-semibold text-slate-400 hover:text-slate-600 mt-4">
                &larr; Kembali ke Keranjang
            </a>
        </div>
    </div>
</form>
@endsection
