@extends('layouts.gudang')
@section('title', 'Dashboard Gudang')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Gudang</h1>
        <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-blue-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Produk</p>
            <p class="text-2xl font-black text-gray-800">{{ $stats['total_produk'] }}</p>
        </div>
        <div class="card p-5 border-l-4 border-green-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Stok Aman</p>
            <p class="text-2xl font-black text-green-600">{{ $stats['stok_aman'] }}</p>
        </div>
        <div class="card p-5 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Stok Menipis</p>
            <p class="text-2xl font-black text-yellow-600">{{ $stats['stok_menipis'] }}</p>
        </div>
        <div class="card p-5 border-l-4 border-red-500">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Stok Habis</p>
            <p class="text-2xl font-black text-red-600">{{ $stats['stok_habis'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Produk Stok Menipis --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4 border-b pb-3">
                <h3 class="font-semibold text-gray-700">⚠ Stok Menipis / Habis</h3>
                <a href="{{ route('gudang.stocks.low-stock') }}" class="text-xs text-orange-600 hover:underline">Lihat Semua</a>
            </div>
            @if($lowStockProducts->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Semua stok aman ✓</p>
            @else
                <div class="space-y-2">
                    @foreach($lowStockProducts as $stock)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $stock->product->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $stock->product->sku }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $stock->quantity == 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                    {{ $stock->quantity }} {{ $stock->unit }}
                                </p>
                                <p class="text-xs text-gray-400">min: {{ $stock->min_stock }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Riwayat Pergerakan Stok --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4 border-b pb-3">
                <h3 class="font-semibold text-gray-700">Riwayat Stok Terbaru</h3>
                <a href="{{ route('gudang.stocks.movements') }}" class="text-xs text-orange-600 hover:underline">Lihat Semua</a>
            </div>
            @if($recentMovements->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Belum ada riwayat</p>
            @else
                <div class="space-y-2">
                    @foreach($recentMovements as $movement)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-xs font-medium text-gray-800">{{ \Str::limit($movement->product->name ?? '-', 30) }}</p>
                                <p class="text-xs text-gray-400">{{ $movement->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                {{ $movement->type === 'in' ? 'bg-green-100 text-green-700' : ($movement->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $movement->type === 'in' ? '+' : ($movement->type === 'out' ? '-' : '~') }}{{ $movement->quantity }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection