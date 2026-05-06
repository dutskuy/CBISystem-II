@extends('layouts.admin')
@section('title', 'Laporan Stok')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Laporan Stok</h2>
        <p class="text-sm text-gray-500">Ringkasan kondisi stok seluruh produk</p>
    </div>
</div>

{{-- Summary --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-blue-700">{{ $summary['total_sku'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total SKU</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-gray-800">{{ number_format($summary['total_qty']) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Stok (pcs)</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ $summary['low_count'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Stok Menipis</p>
    </div>
    <div class="card p-4 text-center">
        <p class="text-2xl font-bold text-red-600">{{ $summary['empty_count'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Stok Habis</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <select name="brand_id" class="form-input w-40">
            <option value="">Semua Brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        <select name="status" class="form-input w-40">
            <option value="">Semua Status</option>
            <option value="safe"  {{ request('status') === 'safe'  ? 'selected' : '' }}>Aman</option>
            <option value="low"   {{ request('status') === 'low'   ? 'selected' : '' }}>Menipis</option>
            <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Habis</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['brand_id','status']))
            <a href="{{ route('admin.reports.stock') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Min</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($stocks as $stock)
                @php
                    $isEmpty = $stock->quantity === 0;
                    $isLow   = !$isEmpty && $stock->quantity <= $stock->min_stock;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800">{{ $stock->product->name }}</p>
                        <p class="text-xs font-mono text-gray-400">{{ $stock->product->sku }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $stock->product->brand->name }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $stock->product->category->name }}</td>
                    <td class="px-6 py-3 text-center font-bold {{ $isEmpty ? 'text-red-600' : ($isLow ? 'text-yellow-600' : 'text-green-600') }}">
                        {{ $stock->quantity }} {{ $stock->unit }}
                    </td>
                    <td class="px-6 py-3 text-center text-gray-500">{{ $stock->min_stock }}</td>
                    <td class="px-6 py-3 text-center">
                        @if($isEmpty)
                            <span class="badge-cancelled">Habis</span>
                        @elseif($isLow)
                            <span class="badge-pending">Menipis</span>
                        @else
                            <span class="badge-delivered">Aman</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection