@extends('layouts.app')

@section('title', 'Masuk - TokoKita')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke TokoKita</h1>
            <p class="text-sm text-slate-500 mt-2">Gunakan kredensial akun Anda untuk mengakses sistem</p>
        </div>

        @if ($errors->has('auth_error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $errors->first('auth_error') }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition"
                    placeholder="nama@domain.lab">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm transition"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                    <span class="text-sm text-slate-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md shadow-blue-500/20 transition">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center text-sm text-slate-600">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Daftar sekarang</a>
        </div>
    </div>

    <!-- Info Box for Security Lab Testers -->
    <div class="mt-6 p-4 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-600">
        <span class="font-bold text-slate-800">Catatan Lab Praktikum:</span>
        <ul class="list-disc list-inside mt-1 space-y-0.5 text-slate-500">
            <li>Akun demo 1: <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-blue-700">budi.santoso@tokokita.lab</code> (password: <code class="bg-white px-1 py-0.5 rounded border border-slate-200">password123</code>)</li>
            <li>Akun demo 2: <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-blue-700">siti.rahmawati@tokokita.lab</code> (password: <code class="bg-white px-1 py-0.5 rounded border border-slate-200">password123</code>)</li>
            <li>Pesan error login digeneralisasi untuk memitigasi account enumeration.</li>
        </ul>
    </div>
</div>
@endsection
