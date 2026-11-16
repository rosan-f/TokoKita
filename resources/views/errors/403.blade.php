@extends('layouts.app')

@section('title', '403 Akses Ditolak - TokoKita')

@section('content')
<div class="max-w-md mx-auto my-12 text-center bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
    <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-amber-200">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-6a4 4 0 10-8 0v4h8V9z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-extrabold text-slate-900">403 - Akses Ditolak</h1>
    <p class="text-sm text-slate-500 mt-2 mb-6">
        {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman atau data ini.' }}
    </p>
    <a href="{{ route('products.index') }}" class="inline-block py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
        Kembali ke Katalog
    </a>
</div>
@endsection
