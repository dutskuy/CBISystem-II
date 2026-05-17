<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Bearindo Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: true }">

{{-- SIDEBAR --}}
<aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-blue-900 text-white transition-transform duration-300"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-800">
        <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
            <img src="/logocbi.jpg" alt="">
        </div>
        <div>
            <p class="font-bold text-sm leading-tight">Bearindo System</p>
            <p class="text-blue-300 text-xs">Admin Panel</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

        {{-- Dashboard --}}
            <a href="{{ route('owner.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.dashboard')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

        <div class="px-4 pt-4 pb-1">
                <p class="text-emerald-400 text-xs font-semibold uppercase tracking-widest">Laporan</p>
            </div>

            <a href="{{ route('owner.reports.profit') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.reports.profit')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>

                <span>Laporan Keuntungan</span>
            </a>
            <a href="{{ route('owner.reports.sales') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.reports.sales')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>

                <span>Laporan Penjualan</span>
            </a>
            <a href="{{ route('owner.reports.stock') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.reports.stock')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>

                <span>Laporan Stok</span>
            </a>
            {{-- TRANSAKSI --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-emerald-400 text-xs font-semibold uppercase tracking-widest">Transaksi</p>
            </div>

            <a href="{{ route('owner.orders.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.orders.*')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('owner.invoices.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.invoices.*')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Invoice</span>
            </a>

            {{-- DATA --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-emerald-400 text-xs font-semibold uppercase tracking-widest">Data</p>
            </div>

            <a href="{{ route('owner.customers.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.customers.*')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Customer</span>
            </a>

            <a href="{{ route('owner.products.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.products.*')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Produk</span>
            </a>

            <a href="{{ route('owner.stocks.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                      {{ request()->routeIs('owner.stocks.*')
                         ? 'bg-white bg-opacity-20 text-white font-semibold'
                         : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                <span>Stok</span>
            </a>

        </nav>
    </nav>

    {{-- User Info --}}
    <div class="px-4 py-4 border-t border-blue-800">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-300 truncate">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-300 hover:text-white transition-colors" title="Logout">
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

        {{-- Notifikasi & User --}}
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="p-6">
        {{-- Alert Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
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