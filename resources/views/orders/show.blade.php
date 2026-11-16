@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - TokoKita')

@section('content')
<div class="max-w-3xl mx-auto my-6">
    <!-- Success Banner -->
    <div class="bg-emerald-600 text-white rounded-3xl p-8 mb-8 text-center shadow-lg shadow-emerald-500/20">
        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Pesanan Berhasil Diterima!</h1>
        <p class="text-emerald-100 text-sm mt-1">Terima kasih atas pesanan Anda di TokoKita Lab Keamanan Jaringan.</p>
        <span class="inline-block mt-4 bg-white/10 text-white text-xs font-mono px-3 py-1.5 rounded-lg border border-white/20">
            Nomor Pesanan: <strong>{{ $order->order_number }}</strong>
        </span>
    </div>

    <!-- Order Receipt Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 border-b border-slate-100 gap-2">
            <div>
                <span class="text-xs text-slate-400">Tanggal Transaksi</span>
                <p class="font-bold text-slate-800 text-sm">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div>
                <span class="text-xs text-slate-400">Status Transaksi</span>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400">Metode Pembayaran</span>
                <p class="font-bold text-slate-800 text-sm">{{ $order->payment_method }}</p>
            </div>
        </div>

        <!-- Shipping Details -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-1">
            <span class="font-bold text-slate-800 block text-sm mb-2">Tujuan Pengiriman</span>
            <p><strong class="text-slate-700">Penerima:</strong> {{ $order->shipping_name }} ({{ $order->shipping_phone }})</p>
            <p><strong class="text-slate-700">Alamat:</strong> {{ $order->shipping_address }}</p>
            @if($order->notes)
                <p><strong class="text-slate-700">Catatan:</strong> {{ $order->notes }}</p>
            @endif
        </div>

        <!-- Items Table -->
        <div>
            <h2 class="text-sm font-bold text-slate-900 mb-3">Rincian Barang yang Dipesan</h2>
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-xs divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-slate-600 font-semibold">
                        <tr>
                            <th class="p-3">Produk</th>
                            <th class="p-3 text-right">Harga Satuan</th>
                            <th class="p-3 text-center">Jumlah</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="p-3 font-semibold text-slate-800">{{ $item->product_name }}</td>
                                <td class="p-3 text-right text-slate-600">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-center text-slate-800 font-bold">{{ $item->quantity }}</td>
                                <td class="p-3 text-right font-extrabold text-blue-600">{{ $item->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold text-slate-900">
                        <tr>
                            <td colspan="3" class="p-3 text-right">Total Pembayaran</td>
                            <td class="p-3 text-right text-base text-blue-600 font-extrabold">{{ $order->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('profile.show') }}" class="text-xs font-semibold text-slate-600 hover:text-blue-600">
                &larr; Lihat Riwayat Pesanan di Profil
            </a>
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                Kembali ke Katalog Produk
            </a>
        </div>
    </div>
</div>
@endsection
