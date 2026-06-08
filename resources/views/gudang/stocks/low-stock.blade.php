@extends('layouts.gudang')
@section('title', 'Stok Menipis')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Stok Menipis & Habis</h1>
        <p class="text-gray-500 text-sm mt-1">Produk yang perlu segera direstok</p>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Min. Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kekurangan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $stock)
                    @if(!$stock->product) @continue @endif
                    <tr class="hover:bg-gray-50 {{ $stock->quantity == 0 ? 'bg-red-50' : 'bg-yellow-50' }}">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $stock->product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $stock->product->sku }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $stock->product->brand->name }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold {{ $stock->quantity == 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                {{ $stock->quantity }} {{ $stock->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $stock->min_stock }} {{ $stock->unit }}</td>
                        <td class="px-6 py-4">
                            <span class="text-red-600 font-bold">
                                -{{ max(0, $stock->min_stock - $stock->quantity) }} {{ $stock->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('gudang.stocks.adjust', $stock->product) }}"
                               class="btn-primary text-xs py-1.5 px-3">Tambah Stok</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <p class="text-green-600 font-semibold">✓ Semua stok dalam kondisi aman!</p>
                        </td>
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