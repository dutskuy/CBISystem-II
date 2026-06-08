@extends('layouts.gudang')
@section('title', 'Data Stok')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Data Stok</h1>

    {{-- Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Produk</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $summary['safe'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Stok Aman</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-yellow-500">{{ $summary['low'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Stok Menipis</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-red-500">{{ $summary['empty'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Stok Habis</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau SKU..." class="form-input flex-1 min-w-48">
            <select name="type" class="form-input w-40" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="safe"  {{ request('type') === 'safe'  ? 'selected' : '' }}>Aman</option>
                <option value="low"   {{ request('type') === 'low'   ? 'selected' : '' }}>Menipis</option>
                <option value="empty" {{ request('type') === 'empty' ? 'selected' : '' }}>Habis</option>
            </select>
            <button type="submit" class="btn-primary">Cari</button>
            @if(request()->hasAny(['search','type']))
                <a href="{{ route('gudang.stocks.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Min. Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($stocks as $stock)
                    @if(!$stock->product) @continue @endif
                    <tr class="hover:bg-gray-50 {{ $stock->quantity == 0 ? 'bg-red-50' : ($stock->quantity <= $stock->min_stock ? 'bg-yellow-50' : '') }}">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $stock->product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $stock->product->sku }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $stock->product->brand->name }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-sm {{ $stock->quantity == 0 ? 'text-red-600' : ($stock->quantity <= $stock->min_stock ? 'text-yellow-600' : 'text-gray-800') }}">
                                {{ $stock->quantity }} {{ $stock->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $stock->min_stock }} {{ $stock->unit }}</td>
                        <td class="px-6 py-4">
                            @if($stock->quantity == 0)
                                <span class="badge-cancelled">Habis</span>
                            @elseif($stock->quantity <= $stock->min_stock)
                                <span class="badge-pending">Menipis</span>
                            @else
                                <span class="badge-delivered">Aman</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('gudang.stocks.adjust', $stock->product) }}"
                               class="text-orange-600 hover:underline text-xs font-medium">Sesuaikan</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada data stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($stocks->hasPages())
            <div class="px-6 py-4 border-t">{{ $stocks->links() }}</div>
        @endif
    </div>
</div>
@endsection