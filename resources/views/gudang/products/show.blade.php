@extends('layouts.gudang')
@section('title', 'Detail Produk')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('gudang.products.index') }}" class="text-sm text-orange-600 hover:underline">← Daftar Produk</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">{{ $product->name }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{-- Info Produk --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Informasi Produk</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400">Brand</p><p class="font-semibold text-blue-600">{{ $product->brand->name }}</p></div>
                    <div><p class="text-xs text-gray-400">Kategori</p><p class="font-medium">{{ $product->category->name }}</p></div>
                    <div><p class="text-xs text-gray-400">SKU</p><p class="font-mono font-medium">{{ $product->sku }}</p></div>
                    <div><p class="text-xs text-gray-400">Part Number</p><p class="font-mono font-medium">{{ $product->part_number ?? '-' }}</p></div>
                </div>
                @if($product->description)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
                        <p class="text-sm text-gray-600">{{ $product->description }}</p>
                    </div>
                @endif
                @if($product->specification)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs text-gray-400 mb-1">Spesifikasi</p>
                        <pre class="text-xs text-gray-600 font-mono bg-gray-50 p-3 rounded-lg whitespace-pre-wrap">{{ $product->specification }}</pre>
                    </div>
                @endif
            </div>

            {{-- Riwayat Stok --}}
            <div class="card p-0 overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-700">Riwayat Pergerakan Stok</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Sebelum</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Sesudah</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Oleh</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($movements as $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <span class="text-xs font-bold px-2 py-1 rounded-full
                                        {{ $m->type === 'in' ? 'bg-green-100 text-green-700' : ($m->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $m->type === 'in' ? 'Masuk' : ($m->type === 'out' ? 'Keluar' : 'Koreksi') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 font-bold">{{ $m->quantity }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $m->stock_before }}</td>
                                <td class="px-6 py-3 font-semibold">{{ $m->stock_after }}</td>
                                <td class="px-6 py-3 text-xs text-gray-500">{{ $m->createdBy->name ?? '-' }}</td>
                                <td class="px-6 py-3 text-xs text-gray-400">{{ $m->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kanan: Stok & Aksi --}}
        <div class="space-y-4">
            <div class="card text-center">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}"
                         class="w-32 h-32 object-contain mx-auto mb-4 rounded-lg border border-gray-100">
                @endif
                @if($product->stock)
                    <p class="text-xs text-gray-400 mb-1">Stok Saat Ini</p>
                    <p class="text-4xl font-black {{ $product->stock->quantity == 0 ? 'text-red-600' : ($product->stock->quantity <= $product->stock->min_stock ? 'text-yellow-600' : 'text-gray-800') }}">
                        {{ $product->stock->quantity }}
                    </p>
                    <p class="text-gray-400 text-sm">{{ $product->stock->unit }}</p>
                    <p class="text-xs text-gray-400 mt-2">Min. stok: {{ $product->stock->min_stock }} {{ $product->stock->unit }}</p>
                @endif
            </div>

            <a href="{{ route('gudang.stocks.adjust', $product) }}"
               class="btn-primary w-full text-center block">
                Sesuaikan Stok
            </a>
        </div>

    </div>
</div>
@endsection