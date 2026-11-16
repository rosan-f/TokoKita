@extends('layouts.app')

@section('title', 'Katalog Produk Hardware & Keamanan Jaringan - tokoKita')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 text-white rounded-3xl p-8 sm:p-12 mb-8 overflow-hidden shadow-xl border border-slate-800">
    <div class="relative z-10 max-w-2xl">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-400/30 mb-4">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
            </svg>
            Official Hardware & Networking Lab Store
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Perangkat Jaringan & Keamanan Sistem</h1>
        <p class="mt-3 text-slate-300 text-sm sm:text-base leading-relaxed">
            Menyediakan perlengkapan hardware, perangkat uji penetrasi, switch managed, router, dan aksesori lab praktikum jaringan terpercaya.
        </p>
    </div>
    <!-- Decorative background elements -->
    <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
</div>

<!-- Search and Filter Bar -->
<div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 mb-8 shadow-sm">
    <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- Search Input -->
        <div class="relative w-full md:w-80 lg:w-96">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau deskripsi produk..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </div>

        <!-- Category Badges (Clean Wrap, No Obstructive Scrollbar) -->
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ !request('category') || request('category') === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Kategori
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), ['category' => $cat])) }}"
                    class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('category') === $cat ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </form>
</div>

<!-- Product Grid -->
@if ($products->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center my-8 shadow-sm">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <h3 class="text-lg font-bold text-slate-700">Tidak ada produk yang cocok</h3>
        <p class="text-sm text-slate-500 mt-1">Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
        <a href="{{ route('products.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
            Lihat Semua Produk
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                <!-- Product Image with S3 URL and graceful fallback -->
                <div class="relative bg-slate-100 h-48 overflow-hidden">
                    <img src="{{ $product->image_url }}"
                        onerror="this.onerror=null; this.src='{{ $product->fallback_image_url }}';"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-800 text-[11px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 shadow-xs">
                        {{ $product->category }}
                    </span>
                    @if ($product->stock > 0)
                        <span class="absolute bottom-3 right-3 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                            Stok: {{ $product->stock }}
                        </span>
                    @else
                        <span class="absolute bottom-3 right-3 bg-rose-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                            Habis
                        </span>
                    @endif
                </div>

                <!-- Product Body -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2">
                            <a href="{{ route('products.show', $product) }}">
                                {{ $product->name }}
                            </a>
                        </h2>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="text-xs text-slate-400 font-medium">Harga</span>
                            <span class="text-lg font-extrabold text-blue-600">
                                {{ $product->formatted_price }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('products.show', $product) }}"
                                class="flex-1 py-2 text-center text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                Detail
                            </a>

                            @auth
                                @if ($product->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            + Keranjang
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="flex-1 py-2 text-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition">
                                    Beli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
@endsection
