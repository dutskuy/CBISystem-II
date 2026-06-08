<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Bearindo Gudang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: true }">

{{-- SIDEBAR --}}
<aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-blue-900 text-white transition-transform duration-300"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-800">
        <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
            <img src="/logocbi.jpg" alt="Logo">
        </div>
        <div>
            <p class="font-bold text-sm leading-tight">Bearindo System</p>
            <p class="text-blue-300 text-xs">Admin Gudang</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto pb-24">

        {{-- Dashboard --}}
        <a href="{{ route('gudang.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('gudang.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Katalog --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Katalog</p>

        <a href="{{ route('gudang.products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('gudang.products.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Daftar Produk
        </a>

        {{-- Manajemen Stok --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Manajemen Stok</p>

        <a href="{{ route('gudang.stocks.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('gudang.stocks.index') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
            </svg>
            Data Stok
        </a>

        <a href="{{ route('gudang.stocks.movements') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('gudang.stocks.movements') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Riwayat Stok
        </a>

        <a href="{{ route('gudang.stocks.low-stock') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('gudang.stocks.low-stock') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Stok Menipis
        </a>

        {{-- Laporan --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Laporan</p>

        <a href="{{ route('gudang.reports.stock') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('gudang.reports.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Laporan Stok
        </a>

    </nav>

    {{-- User Info --}}
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700 bg-blue-900">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-blue-300 text-xs">Admin Gudang</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                @csrf
                <button type="submit"
                        class="text-blue-300 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-blue-800"
                        title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN CONTENT --}}
<div class="transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">

    {{-- TOPBAR --}}
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Breadcrumb --}}
        <div class="flex-1">
            <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
            @hasSection('breadcrumb')
                <div class="text-sm text-gray-500">@yield('breadcrumb')</div>
            @endif
        </div>

        {{-- Waktu --}}
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="p-6">
        {{-- Alert Messages dengan Auto-hide 4 detik mengikuti Layout Admin --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>