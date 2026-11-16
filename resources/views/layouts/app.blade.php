<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'tokoKita - Toko Hardware & Keamanan Jaringan')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased">

    <!-- Top Lab Banner -->
    <div class="bg-indigo-950 text-indigo-200 text-xs py-1.5 px-4 text-center border-b border-indigo-900">
        <span class="font-bold text-white uppercase tracking-wider bg-indigo-800 px-2 py-0.5 rounded text-[10px] mr-2">Lab Security</span>
        Environment Praktikum Keamanan Jaringan &bull; Edukasi &bull; Data Fiktif &bull; Localhost / Cloud Instance
    </div>

    <!-- Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-slate-900">toko<span class="text-blue-600">Kita</span></span>
                            <span class="block text-[10px] font-semibold tracking-wider text-slate-400 uppercase -mt-1">NetSec Store</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('products.*') ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                            Katalog Produk
                        </a>
                    </nav>
                </div>

                <!-- Right Side Navigation: Search / Cart / User -->
                <div class="flex items-center gap-4">
                    <!-- Cart Button (for logged-in users) -->
                    @auth
                    @php
                        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded-xl transition" title="Keranjang Belanja">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white font-bold text-[11px] w-5 h-5 rounded-full flex items-center justify-center ring-2 ring-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    @endauth

                    <!-- User Menu / Auth Links -->
                    @auth
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 p-1.5 pr-3 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-sm flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-3 py-2 rounded-xl transition border border-rose-200">
                                Logout
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 px-3 py-2 rounded-xl hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl shadow-sm shadow-blue-500/20 transition">
                            Daftar
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Notifications / Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('info') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:flex sm:justify-between sm:items-center text-sm text-slate-500">
            <div>
                <p class="font-semibold text-slate-800">tokoKita &bull; Lab Praktikum Keamanan Jaringan</p>
                <p class="text-xs text-slate-400 mt-1">Dibuat untuk praktikum edukasi keamanan web & audit jaringan.</p>
            </div>
            <div class="mt-4 sm:mt-0 text-xs text-slate-400 flex justify-center gap-4">
                <span>Status Keamanan: Terproteksi</span>
                <span>&bull;</span>
                <span>Mode: Production Ready</span>
            </div>
        </div>
    </footer>

</body>
</html>
