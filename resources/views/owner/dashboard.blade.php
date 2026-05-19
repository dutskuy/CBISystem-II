@extends('layouts.owner')
@section('title', 'Dashboard Owner')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }} </h1>
        <p class="text-gray-500 text-sm mt-1">Berikut ringkasan performa bisnis Bearindo.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="card p-5 border-l-4 border-emerald-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Revenue</p>
            <p class="text-2xl font-black text-gray-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua transaksi selesai</p>
        </div>
        <div class="card p-5 border-l-4 border-green-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Keuntungan</p>
            <p class="text-2xl font-black text-green-600">Rp {{ number_format($stats['total_profit'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Setelah dikurangi modal</p>
        </div>
        <div class="card p-5 border-l-4 border-blue-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Pesanan</p>
            <p class="text-2xl font-black text-gray-800">{{ $stats['total_orders'] }}</p>
            <p class="text-xs text-yellow-500 mt-1">{{ $stats['pending_orders'] }} pending</p>
        </div>
        <div class="card p-5 border-l-4 border-purple-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Customer</p>
            <p class="text-2xl font-black text-gray-800">{{ $stats['total_customers'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Customer terdaftar</p>
        </div>
        <div class="card p-5 border-l-4 border-red-400">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Stok Menipis</p>
            <p class="text-2xl font-black {{ $stats['low_stock'] > 0 ? 'text-red-500' : 'text-gray-800' }}">
                {{ $stats['low_stock'] }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Produk perlu restok</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top Produk --}}
        <div class="card">
            <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Top 5 Produk Terlaris</h3>
            @if($topProducts->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
            @else
                <div class="space-y-3">
                    @foreach($topProducts as $i => $product)
                        @php $maxQty = $topProducts->first()->total_qty ?: 1; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold flex items-center justify-center">
                                        {{ $i+1 }}
                                    </span>
                                    <p class="font-medium text-gray-800 text-xs">{{ \Str::limit($product->product_name, 30) }}</p>
                                </div>
                                <p class="font-bold text-gray-800 text-xs">{{ number_format($product->total_qty) }} pcs</p>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-emerald-500 h-1.5 rounded-full"
                                     style="width: {{ ($product->total_qty / $maxQty) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pesanan Terbaru --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4 border-b pb-3">
                <h3 class="font-semibold text-gray-700">Pesanan Terbaru</h3>
                <a href="{{ route('owner.orders.index') }}" class="text-xs text-emerald-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-2">
                @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-xs font-mono font-semibold text-emerald-700">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="badge-{{ $order->status }} text-xs">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection