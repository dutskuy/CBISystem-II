@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Total Produk --}}
    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Produk</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_products']) }}</p>
        </div>
    </div>

    {{-- Total Pesanan --}}
    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pesanan</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</p>
            @if($stats['pending_orders'] > 0)
                <p class="text-xs text-yellow-600 font-medium">{{ $stats['pending_orders'] }} menunggu</p>
            @endif
        </div>
    </div>

    {{-- Revenue Bulan Ini --}}
    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Revenue Bulan Ini</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Stok Menipis --}}
    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 {{ $stats['low_stock_count'] > 0 ? 'bg-red-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 {{ $stats['low_stock_count'] > 0 ? 'text-red-700' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Stok Menipis</p>
            <p class="text-2xl font-bold {{ $stats['low_stock_count'] > 0 ? 'text-red-700' : 'text-gray-800' }}">
                {{ $stats['low_stock_count'] }}
            </p>
            @if($stats['low_stock_count'] > 0)
                <a href="{{ route('admin.stocks.low-stock') }}" class="text-xs text-red-600 hover:underline">Lihat detail →</a>
            @endif
        </div>
    </div>

</div>

{{-- Tables Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Pesanan Terbaru --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua →</a>
        </div>

        @if($recent_orders->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">Belum ada pesanan</p>
        @else
            <div class="space-y-3">
                @foreach($recent_orders as $order)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->name }} · {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Stok Menipis --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Peringatan Stok Menipis</h3>
            <a href="{{ route('admin.stocks.low-stock') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua →</a>
        </div>

        @if($low_stock_products->isEmpty())
            <div class="flex flex-col items-center justify-center py-6 text-green-600">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Semua stok dalam kondisi aman</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($low_stock_products as $stock)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $stock->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $stock->product->brand->name }} · SKU: {{ $stock->product->sku }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-red-600">{{ $stock->quantity }} {{ $stock->unit }}</p>
                            <p class="text-xs text-gray-400">Min: {{ $stock->min_stock }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection