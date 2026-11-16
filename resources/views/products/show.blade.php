@extends('layouts.app')

@section('title', $product->name . ' - tokoKita')

@section('content')
<!-- Breadcrumbs -->
<nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
    <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition">Beranda</a>
    <span>&rsaquo;</span>
    <a href="{{ route('products.index', ['category' => $product->category]) }}" class="hover:text-blue-600 transition">{{ $product->category }}</a>
    <span>&rsaquo;</span>
    <span class="text-slate-800 font-semibold truncate max-w-xs">{{ $product->name }}</span>
</nav>

<div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm mb-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-start">
        <!-- Product Image -->
        <div class="bg-slate-100 rounded-2xl overflow-hidden aspect-video sm:aspect-square flex items-center justify-center border border-slate-200">
            <img src="{{ $product->image_url }}"
                onerror="this.onerror=null; this.src='{{ $product->fallback_image_url }}';"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover">
        </div>

        <!-- Product Information & Action -->
        <div class="flex flex-col justify-between h-full">
            <div>
                <span class="inline-block bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-lg border border-blue-200 mb-3">
                    {{ $product->category }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                    {{ $product->name }}
                </h1>

                <div class="mt-4 flex items-baseline gap-4">
                    <span class="text-3xl font-extrabold text-blue-600">
                        {{ $product->formatted_price }}
                    </span>
                    @if ($product->stock > 0)
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">
                            Stok Tersedia ({{ $product->stock }} unit)
                        </span>
                    @else
                        <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-lg">
                            Stok Habis
                        </span>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 mb-2">Deskripsi Produk</h2>
                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>
            </div>

            <!-- Add to Cart Form -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                @if ($product->stock > 0)
                    @auth
                        <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="w-full sm:w-32">
                                <label for="quantity" class="block text-xs font-semibold text-slate-500 mb-1">Jumlah</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                    class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-center font-bold text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600">
                            </div>

                            <div class="flex-1 self-end w-full">
                                <button type="submit" class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                            <p class="text-sm text-slate-600 mb-3">Silakan masuk dengan akun Anda untuk menambahkan produk ini ke keranjang.</p>
                            <a href="{{ route('login') }}" class="inline-block py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
                                Masuk untuk Membeli
                            </a>
                        </div>
                    @endauth
                @else
                    <div class="p-4 bg-slate-100 rounded-2xl text-center text-slate-500 text-sm font-semibold">
                        Maaf, stok produk ini sedang habis saat ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if ($relatedProducts->isNotEmpty())
    <div>
        <h2 class="text-xl font-bold text-slate-900 mb-6">Produk Terkait dalam Kategori Serupa</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($relatedProducts as $rel)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div class="bg-slate-100 h-36 overflow-hidden">
                        <img src="{{ $rel->image_url }}"
                            onerror="this.onerror=null; this.src='{{ $rel->fallback_image_url }}';"
                            alt="{{ $rel->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex flex-col justify-between flex-1">
                        <h3 class="font-bold text-sm text-slate-900 line-clamp-1">
                            <a href="{{ route('products.show', $rel) }}" class="hover:text-blue-600 transition">{{ $rel->name }}</a>
                        </h3>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm font-extrabold text-blue-600">{{ $rel->formatted_price }}</span>
                            <a href="{{ route('products.show', $rel) }}" class="text-xs font-semibold text-slate-600 hover:text-blue-600">Detail &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
