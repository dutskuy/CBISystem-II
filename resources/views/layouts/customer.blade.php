<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bearindo') — PT Central Bearindo International</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans" x-data="{}">

{{-- NAVBAR --}}
<nav class="bg-blue-900 text-white sticky top-0 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                    <span class="text-blue-900 font-black text-sm">CBI</span>
                </div>
                <div class="hidden sm:block">
                    <p class="font-bold text-sm leading-tight">PT Central Bearindo</p>
                    <p class="text-blue-300 text-xs">International</p>
                </div>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('customer.dashboard') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('customer.dashboard') ? 'text-white' : 'text-blue-200 hover:text-white' }}">
                    Beranda
                </a>
                <a href="{{ route('customer.products.index') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('customer.products.*') ? 'text-white' : 'text-blue-200 hover:text-white' }}">
                    Produk
                </a>
                <a href="{{ route('customer.orders.index') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('customer.orders.*') ? 'text-white' : 'text-blue-200 hover:text-white' }}">
                    Pesanan
                </a>
                <a href="{{ route('customer.invoices.index') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('customer.invoices.*') ? 'text-white' : 'text-blue-200 hover:text-white' }}">
                    Invoice
                </a>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-4">
                {{-- Cart Icon --}}
                <a href="{{ route('customer.cart.index') }}" class="relative text-blue-200 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @php
                        $cartCount = auth()->user()
                            ? \App\Models\Cart::where('user_id', auth()->id())
                                ->with('items')->first()?->items->sum('quantity') ?? 0
                            : 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-blue-200 hover:text-white transition-colors">
                        <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
<main class="min-h-screen">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="max-w-7xl mx-auto px-4 pt-4">
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="max-w-7xl mx-auto px-4 pt-4">
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-blue-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-blue-900 font-black text-sm">CBI</span>
                    </div>
                    <div>
                        <p class="font-bold text-sm">PT Central Bearindo International</p>
                        <p class="text-blue-300 text-xs">Distributor Resmi sejak 1994</p>
                    </div>
                </div>
                <p class="text-blue-200 text-sm">Distributor resmi dan eksklusif bearing, conveyor belt, dan power transmission terkemuka di Indonesia.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Brand Kami</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(['FAG','INA','LUK','NACHI','FYH','FBJ'] as $b)
                        <span class="bg-blue-800 text-blue-200 text-xs px-2 py-1 rounded">{{ $b }}</span>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Kontak</h4>
                <p class="text-blue-200 text-sm">Jakarta, Indonesia</p>
                <p class="text-blue-200 text-sm">Beroperasi sejak 1994</p>
            </div>
        </div>
        <div class="border-t border-blue-800 mt-8 pt-4 text-center text-blue-400 text-sm">
            © {{ date('Y') }} PT Central Bearindo International. All rights reserved.
        </div>
    </div>
</footer>

</body>
</html>