@extends('layouts.app')

@section('title', 'Profil Pengguna - TokoKita')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Profil Pengguna</h1>
    <p class="text-sm text-slate-500 mt-1">Kelola data profil dan tinjau riwayat transaksi Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    <!-- User Profile Form -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-md shadow-blue-500/20">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-extrabold text-slate-900 text-lg leading-tight">{{ $user->name }}</h2>
                <span class="text-xs text-slate-400 font-mono">{{ $user->email }}</span>
                <span class="block mt-1 text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md w-fit">User ID: #{{ $user->id }}</span>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-600 mb-1">Alamat Email (Tetap)</label>
                <input type="email" id="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm cursor-not-allowed">
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-600 mb-1">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                    placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-slate-600 mb-1">Alamat Default</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                Simpan Perubahan
            </button>
        </form>

        <!-- Lab Security Notice -->
        <div class="mt-6 pt-6 border-t border-slate-100">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-[11px] text-slate-500 space-y-1">
                <span class="font-bold text-slate-700 block">Uji Keamanan Lab (Anti-IDOR):</span>
                <p>Endpoint API: <code class="bg-white px-1 py-0.5 rounded border border-slate-200 font-mono text-blue-600">GET /api/users/{{ $user->id }}</code></p>
                <p>Hanya mengembalikan data milik Anda. Mengakses ID lain menghasilkan HTTP 403 Forbidden.</p>
            </div>
        </div>
    </div>

    <!-- Order History List -->
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center justify-between">
            <span>Riwayat Pesanan Anda</span>
            <span class="text-xs font-normal text-slate-400">Total: {{ $orders->total() }} pesanan</span>
        </h2>

        @if ($orders->isEmpty())
            <div class="p-10 text-center border border-dashed border-slate-200 rounded-2xl">
                <p class="text-sm text-slate-500 mb-3">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('products.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold text-xs rounded-xl hover:bg-blue-700 transition">
                    Beli Produk Sekarang
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="border border-slate-200 rounded-2xl p-5 hover:border-slate-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-extrabold text-sm text-slate-900 font-mono">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ $order->created_at->format('d M Y, H:i') }} &bull; {{ $order->items_count }} item barang &bull; {{ $order->payment_method }}
                            </p>
                            <p class="text-sm font-extrabold text-blue-600 mt-2">
                                {{ $order->formatted_total }}
                            </p>
                        </div>

                        <div>
                            <a href="{{ route('orders.show', $order) }}" class="inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                                Lihat Rincian &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
