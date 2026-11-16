@extends('layouts.app')

@section('title', '500 Kesalahan Server - TokoKita')

@section('content')
<div class="max-w-md mx-auto my-12 text-center bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
    <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-rose-200">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-extrabold text-slate-900">500 - Terjadi Kesalahan Server</h1>
    <p class="text-sm text-slate-500 mt-2 mb-6">
        Mohon maaf, sistem mengalami gangguan internal. Silakan coba kembali beberapa saat lagi.
    </p>
    <a href="{{ route('products.index') }}" class="inline-block py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition">
        Kembali ke Katalog
    </a>
</div>
@endsection
