@extends('layouts.owner')
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

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Min. Stok</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($stocks as $stock)
                    @if(!$stock->product) @continue @endif
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 text-sm">{{ $stock->product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $stock->product->sku }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-blue-600 font-semibold">{{ $stock->product->brand->name }}</td>
                        <td class="px-6 py-4 font-bold text-sm">{{ $stock->quantity }} {{ $stock->unit }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada data stok.</td>
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