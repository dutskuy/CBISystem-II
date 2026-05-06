@extends('layouts.admin')
@section('title', 'Detail Produk')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
            <h2 class="text-xl font-bold text-gray-800 mt-2">{{ $product->name }}</h2>
        </div>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary">Edit Produk</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Foto & Info --}}
        <div class="space-y-4">
            <div class="card">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                         class="w-full aspect-square object-contain rounded-lg">
                @else
                    <div class="w-full aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Stok Card --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-3">Informasi Stok</h3>
                @if($product->stock)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stok Saat Ini</span>
                            <span class="font-bold {{ $product->stock->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                                {{ $product->stock->quantity }} {{ $product->stock->unit }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stok Minimum</span>
                            <span class="font-medium">{{ $product->stock->min_stock }} {{ $product->stock->unit }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.stocks.adjust', $product) }}" class="btn-secondary w-full text-center mt-4 block text-sm">
                        Sesuaikan Stok
                    </a>
                @endif
            </div>
        </div>

        {{-- Detail --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-4 border-b border-gray-100 pb-3">Detail Produk</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">Brand</dt><dd class="font-medium">{{ $product->brand->name }}</dd></div>
                    <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">Kategori</dt><dd class="font-medium">{{ $product->category->name }}</dd></div>
                    <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">SKU</dt><dd class="font-mono">{{ $product->sku }}</dd></div>
                    <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">Part Number</dt><dd class="font-mono">{{ $product->part_number ?? '-' }}</dd></div>
                    <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">Harga</dt><dd class="font-bold text-blue-700 text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</dd></div>
                    <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">Status</dt>
                        <dd>@if($product->is_active)<span class="badge-delivered">Aktif</span>@else<span class="badge-cancelled">Nonaktif</span>@endif</dd>
                    </div>
                    @if($product->description)
                        <div class="flex gap-4"><dt class="w-32 text-gray-500 flex-shrink-0">Deskripsi</dt><dd>{{ $product->description }}</dd></div>
                    @endif
                    @if($product->specification)
                        <div class="flex gap-4">
                            <dt class="w-32 text-gray-500 flex-shrink-0">Spesifikasi</dt>
                            <dd class="font-mono text-xs bg-gray-50 p-3 rounded-lg flex-1 whitespace-pre-line">{{ $product->specification }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Riwayat Stok --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-4 border-b border-gray-100 pb-3">Riwayat Pergerakan Stok</h3>
                @if($movements->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat stok</p>
                @else
                    <div class="space-y-2">
                        @foreach($movements as $mov)
                            <div class="flex items-center justify-between text-sm py-2 border-b border-gray-50 last:border-0">
                                <div>
                                    <span class="{{ $mov->type === 'in' ? 'text-green-600' : ($mov->type === 'out' ? 'text-red-600' : 'text-yellow-600') }} font-medium uppercase text-xs">
                                        {{ $mov->type }}
                                    </span>
                                    <span class="ml-2 text-gray-600">{{ $mov->notes ?? $mov->reference ?? '-' }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="{{ $mov->type === 'in' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $mov->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection