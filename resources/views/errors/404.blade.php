@extends('layouts.app')

@section('title', '404 Tidak Ditemukan - TokoKita')

@section('content')
<div class="max-w-md mx-auto my-12 text-center bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-200">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-extrabold text-slate-900">404 - Halaman Tidak Ditemukan</h1>
    <p class="text-sm text-slate-500 mt-2 mb-6">
        Halaman atau sumber daya yang Anda tuju tidak ditemukan pada sistem.
    </p>
    <a href="{{ route('products.index') }}" class="inline-block py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
        Kembali ke Katalog
    </a>
</div>
@endsection
