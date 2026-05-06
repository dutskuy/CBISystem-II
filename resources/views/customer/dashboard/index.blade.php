@extends('layouts.customer')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Welcome --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-8 text-white mb-8">
        <h1 class="text-2xl font-bold mb-1">Selamat datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-blue-200">Temukan bearing dan komponen industri berkualitas dari brand terpercaya dunia.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="card text-center">
            <p class="text-3xl font-bold text-blue-700">{{ $stats['total_orders'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Pesanan</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Menunggu Konfirmasi</p>
        </div>
        <div class="card text-center">
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Pembelian</p>
        </div>
    </div>

    {{-- Featured Products --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Produk Terbaru</h2>
            <a href="{{ route('customer.products.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua →</a>
        </div>

        @if($featured_products->isEmpty())
            <p class="text-gray-400 text-sm">Belum ada produk tersedia.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($featured_products as $product)
                    <a href="{{ route('customer.products.show', $product) }}"
                       class="card hover:shadow-md transition-shadow group p-4">
                        <div class="w-full aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                            @else
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-xs text-blue-600 font-medium">{{ $product->brand->name }}</span>
                        <p class="text-sm font-medium text-gray-800 mt-1 line-clamp-2">{{ $product->name }}</p>
                        <p class="text-sm font-bold text-blue-900 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Orders --}}
    @if($recent_orders->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Pesanan Terakhir</h2>
                <a href="{{ route('customer.orders.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="card p-0 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recent_orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-blue-700">
                                    <a href="{{ route('customer.orders.show', $order) }}">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3 font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-3"><span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection