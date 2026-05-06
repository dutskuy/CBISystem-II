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
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Katalog --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Katalog</p>

        <a href="{{ route('admin.brands.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.brands.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Brand
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Kategori
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.products.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Produk
        </a>

        {{-- Section PENGGUNA --}}
        <div class="px-4 pt-4 pb-1">
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-widest">Pengguna</p>
        </div>

        <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('admin.users.*')
                    ? 'bg-white bg-opacity-20 text-white font-semibold'
                    : 'text-blue-100 hover:bg-white hover:bg-opacity-10' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Users</span>
        </a>
        
        {{-- Inventori --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Inventori</p>

        <a href="{{ route('admin.stocks.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.stocks.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Manajemen Stok
            @php $lowStock = \App\Models\ProductStock::whereColumn('quantity','<=','min_stock')->count(); @endphp
            @if($lowStock > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $lowStock }}</span>
            @endif
        </a>

        {{-- Penjualan --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Penjualan</p>

        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.orders.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Pesanan
            @php $pendingOrders = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pendingOrders > 0)
                <span class="ml-auto bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingOrders }}</span>
            @endif
        </a>
        
        <a href="{{ route('admin.payments.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.payments.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Verifikasi Pembayaran
        </a>

        <a href="{{ route('admin.invoices.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Invoice
        </a>

        {{-- Laporan --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Laporan</p>

        <a href="{{ route('admin.reports.sales') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.reports.sales*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Laporan Penjualan
        </a>

        <a href="{{ route('admin.reports.stock') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.reports.stock') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Laporan Stok
        </a>

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