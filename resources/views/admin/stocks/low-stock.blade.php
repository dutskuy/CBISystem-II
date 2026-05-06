@extends('layouts.admin')
@section('title', 'Stok Menipis')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">⚠️ Peringatan Stok Menipis</h2>
        <p class="text-sm text-gray-500">Produk dengan stok di bawah atau sama dengan batas minimum</p>
    </div>
    <a href="{{ route('admin.stocks.index') }}" class="btn-secondary text-sm">← Kembali ke Stok</a>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-yellow-50 border-b border-yellow-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-yellow-700 uppercase">Produk</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-yellow-700 uppercase">Brand</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-yellow-700 uppercase">Stok Saat Ini</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-yellow-700 uppercase">Stok Min</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-yellow-700 uppercase">Selisih</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-yellow-700 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($stocks as $stock)
                <tr class="hover:bg-gray-50 {{ $stock->quantity === 0 ? 'bg-red-50' : 'bg-yellow-50/30' }}">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $stock->product->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $stock->product->sku }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $stock->product->brand->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xl font-bold {{ $stock->quantity === 0 ? 'text-red-600' : 'text-yellow-600' }}">
                            {{ $stock->quantity }}
                        </span>
                        <span class="text-xs text-gray-400"> {{ $stock->unit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-600">
                        {{ $stock->min_stock }} <span class="text-xs text-gray-400">{{ $stock->unit }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-red-600 font-bold">-{{ $stock->min_stock - $stock->quantity }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.stocks.adjust', $stock->product) }}"
                           class="btn-primary text-xs py-1.5 px-3">
                            Tambah Stok
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="text-green-600 flex flex-col items-center gap-2">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-semibold">Semua stok dalam kondisi aman!</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($stocks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $stocks->links() }}</div>
    @endif
</div>
@endsection