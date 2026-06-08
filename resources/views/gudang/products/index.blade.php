@extends('layouts.gudang')
@section('title', 'Daftar Produk')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>

    {{-- Filter --}}
    <div class="card">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, SKU, part number..."
                   class="form-input flex-1 min-w-48">
            <select name="brand_id" class="form-input w-40" onchange="this.form.submit()">
                <option value="">Semua Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Cari</button>
            @if(request()->hasAny(['search','brand_id','category_id']))
                <a href="{{ route('gudang.products.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">SKU</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         class="w-10 h-10 object-contain rounded-lg border border-gray-100">
                                @else
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                                <p class="font-medium text-gray-800">{{ $product->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $product->brand->name }}</td>
                        <td class="px-6 py-4">
                            @if($product->stock)
                                <span class="font-bold {{ $product->stock->quantity == 0 ? 'text-red-600' : ($product->stock->quantity <= $product->stock->min_stock ? 'text-yellow-600' : 'text-gray-800') }}">
                                    {{ $product->stock->quantity }} {{ $product->stock->unit }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock)
                                @if($product->stock->quantity == 0)
                                    <span class="badge-cancelled">Habis</span>
                                @elseif($product->stock->quantity <= $product->stock->min_stock)
                                    <span class="badge-pending">Menipis</span>
                                @else
                                    <span class="badge-delivered">Aman</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('gudang.products.show', $product) }}"
                                   class="text-blue-600 hover:underline text-xs font-medium">Detail</a>
                                <a href="{{ route('gudang.stocks.adjust', $product) }}"
                                   class="text-orange-600 hover:underline text-xs font-medium">Sesuaikan Stok</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($products->hasPages())
            <div class="px-6 py-4 border-t">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection