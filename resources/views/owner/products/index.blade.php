@extends('layouts.owner')
@section('title', 'Data Produk')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Produk</h1>

    <div class="card">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau SKU..." class="form-input flex-1">
            <button type="submit" class="btn-primary">Cari</button>
            @if(request('search'))
                <a href="{{ route('owner.products.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Harga Jual</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Harga Modal</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Margin</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                    @php
                        $margin = $product->price > 0 && $product->cost_price > 0
                            ? round((($product->price - $product->cost_price) / $product->price) * 100, 1)
                            : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 text-sm">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $product->sku }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-blue-600 font-semibold">{{ $product->brand->name }}</td>
                        <td class="px-6 py-4 font-semibold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $product->cost_price > 0 ? 'Rp '.number_format($product->cost_price, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($margin > 0)
                                <span class="text-xs font-bold {{ $margin >= 20 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-500') }}">
                                    {{ $margin }}%
                                </span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock)
                                <span class="{{ $product->stock->quantity > 0 ? 'text-green-600' : 'text-red-500' }} font-semibold text-sm">
                                    {{ $product->stock->quantity }} {{ $product->stock->unit }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
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