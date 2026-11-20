@extends('layouts.app')

@section('title', 'Keranjang Belanja - TokoKita')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Keranjang Belanja</h1>
    <p class="text-sm text-slate-500 mt-1">Periksa item pilihan Anda sebelum melanjutkan ke proses checkout</p>
</div>

@if ($cartItems->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto shadow-sm my-8">
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-900">Keranjang Anda Masih Kosong</h2>
        <p class="text-sm text-slate-500 mt-2 mb-6">Jelajahi produk hardware dan keamanan jaringan kami untuk menambah pesanan.</p>
        <a href="{{ route('products.index') }}" class="inline-block py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 transition">
            Mulai Belanja Sekarang
        </a>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Cart Items List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-sm">Daftar Barang ({{ $cartItems->count() }} jenis produk)</span>
                    <span class="text-xs text-slate-400">Total Unit: {{ $cartItems->sum('quantity') }}</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($cartItems as $item)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <!-- Image + Info -->
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                    <img src="{{ $item->product->image_url }}"
                                        alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-1">
                                        <a href="{{ route('products.show', $item->product) }}" class="hover:text-blue-600 transition">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <span class="text-xs text-slate-500">{{ $item->product->category }} &bull; {{ $item->product->formatted_price }}</span>
                                </div>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                        class="w-16 px-2 py-1.5 text-center text-sm font-semibold border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition" title="Perbarui Jumlah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Subtotal -->
                                <div class="text-right min-w-25">
                                    <span class="block text-sm font-extrabold text-blue-600">{{ $item->formatted_subtotal }}</span>
                                </div>

                                <!-- Remove Button -->
                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition" title="Hapus dari Keranjang">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center px-2">
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 flex items-center gap-1 transition">
                    &larr; Lanjut Belanja Produk Lain
                </a>
            </div>
        </div>

        <!-- Summary & Checkout Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-extrabold text-slate-900 mb-6">Ringkasan Belanja</h2>

            <div class="space-y-3 text-sm text-slate-600 pb-6 border-b border-slate-100">
                <div class="flex justify-between">
                    <span>Total Harga Barang</span>
                    <span class="font-semibold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Pengiriman (Lab Simulasi)</span>
                    <span class="font-semibold text-emerald-600">Gratis (Rp 0)</span>
                </div>
                <div class="flex justify-between">
                    <span>Pajak (PPN 11%)</span>
                    <span class="font-semibold text-slate-400">Termasuk</span>
                </div>
            </div>

            <div class="flex justify-between items-baseline py-6">
                <span class="text-base font-bold text-slate-900">Total Pembayaran</span>
                <span class="text-2xl font-extrabold text-blue-600">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <a href="{{ route('checkout.index') }}" class="block w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-center rounded-xl shadow-md shadow-blue-500/20 transition">
                Lanjut ke Checkout &rarr;
            </a>

            <div class="mt-4 text-center">
                <span class="text-[11px] text-slate-400 flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Transaksi terenkripsi aman dalam lingkungan lab
                </span>
            </div>
        </div>
    </div>
@endif
@endsection
