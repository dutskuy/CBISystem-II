@extends('layouts.admin')
@section('title', 'Manajemen Stok')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card flex items-center gap-3 p-4">
        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Produk</p>
            <p class="text-xl font-bold text-gray-800">{{ $summary['total'] }}</p>
        </div>
    </div>
    <div class="card flex items-center gap-3 p-4">
        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Stok Aman</p>
            <p class="text-xl font-bold text-green-700">{{ $summary['safe'] }}</p>
        </div>
    </div>
    <div class="card flex items-center gap-3 p-4">
        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Stok Menipis</p>
            <p class="text-xl font-bold text-yellow-700">{{ $summary['low'] }}</p>
        </div>
    </div>
    <div class="card flex items-center gap-3 p-4">
        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Stok Habis</p>
            <p class="text-xl font-bold text-red-700">{{ $summary['empty'] }}</p>
        </div>
    </div>
</div>

{{-- Header & Actions --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Stok</h2>
        <p class="text-sm text-gray-500">Monitor dan sesuaikan stok produk</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.stocks.movements') }}" class="btn-secondary text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Riwayat Pergerakan
        </a>
        <a href="{{ route('admin.stocks.low-stock') }}" class="btn-secondary text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Stok Menipis
            @if($summary['low'] + $summary['empty'] > 0)
                <span class="bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $summary['low'] + $summary['empty'] }}</span>
            @endif
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama produk atau SKU..."
               class="form-input flex-1 min-w-48">
        <select name="brand_id" class="form-input w-40">
            <option value="">Semua Brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        <select name="stock_status" class="form-input w-44">
            <option value="">Semua Status Stok</option>
            <option value="safe"  {{ request('stock_status') === 'safe'  ? 'selected' : '' }}>✅ Aman</option>
            <option value="low"   {{ request('stock_status') === 'low'   ? 'selected' : '' }}>⚠️ Menipis</option>
            <option value="empty" {{ request('stock_status') === 'empty' ? 'selected' : '' }}>❌ Habis</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','brand_id','stock_status']))
            <a href="{{ route('admin.stocks.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">SKU</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok Saat Ini</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok Min</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($stocks as $stock)
                @php
                    $isEmpty = $stock->quantity === 0;
                    $isLow   = !$isEmpty && $stock->quantity <= $stock->min_stock;
                    $isSafe  = $stock->quantity > $stock->min_stock;
                @endphp
                <tr class="hover:bg-gray-50 {{ $isEmpty ? 'bg-red-50' : ($isLow ? 'bg-yellow-50' : '') }}">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $stock->product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $stock->product->brand->name }} · {{ $stock->product->category->name }}</p>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $stock->product->sku }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-lg font-bold {{ $isEmpty ? 'text-red-600' : ($isLow ? 'text-yellow-600' : 'text-green-700') }}">
                            {{ $stock->quantity }}
                        </span>
                        <span class="text-xs text-gray-400"> {{ $stock->unit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-500">
                        {{ $stock->min_stock }} <span class="text-xs">{{ $stock->unit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($isEmpty)
                            <span class="badge-cancelled">Habis</span>
                        @elseif($isLow)
                            <span class="badge-pending">Menipis</span>
                        @else
                            <span class="badge-delivered">Aman</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.stocks.adjust', $stock->product) }}"
                           class="btn-primary text-xs py-1.5 px-3">
                            Sesuaikan
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($stocks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $stocks->links() }}</div>
    @endif
</div>

@endsection