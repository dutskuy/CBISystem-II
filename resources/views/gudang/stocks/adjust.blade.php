@extends('layouts.gudang')
@section('title', 'Sesuaikan Stok')

@section('content')
<div class="max-w-lg">

    <div class="mb-6">
        <a href="{{ route('gudang.stocks.index') }}" class="text-sm text-orange-600 hover:underline">← Data Stok</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">Sesuaikan Stok</h1>
        <p class="text-gray-500 text-sm">{{ $product->name }}</p>
    </div>

    {{-- Info Stok Saat Ini --}}
    <div class="card mb-6 flex items-center gap-4">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}"
                 class="w-16 h-16 object-contain rounded-lg border border-gray-100">
        @endif
        <div>
            <p class="text-xs text-gray-400">Stok Saat Ini</p>
            <p class="text-3xl font-black text-gray-800">
                {{ $product->stock->quantity ?? 0 }}
                <span class="text-lg text-gray-400 font-normal">{{ $product->stock->unit ?? 'pcs' }}</span>
            </p>
            <p class="text-xs text-gray-400">Min. stok: {{ $product->stock->min_stock ?? 0 }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="card" x-data="{ type: 'in' }">
        <form method="POST" action="{{ route('gudang.stocks.store', $product) }}" class="space-y-4">
            @csrf

            {{-- Tipe --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Penyesuaian</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="in" x-model="type" class="sr-only">
                        <div :class="type === 'in' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-600'"
                             class="border-2 rounded-lg p-3 text-center transition-all">
                            <p class="font-bold text-lg">+</p>
                            <p class="text-xs font-medium">Stok Masuk</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="out" x-model="type" class="sr-only">
                        <div :class="type === 'out' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-600'"
                             class="border-2 rounded-lg p-3 text-center transition-all">
                            <p class="font-bold text-lg">−</p>
                            <p class="text-xs font-medium">Stok Keluar</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="adjustment" x-model="type" class="sr-only">
                        <div :class="type === 'adjustment' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600'"
                             class="border-2 rounded-lg p-3 text-center transition-all">
                            <p class="font-bold text-lg">≈</p>
                            <p class="text-xs font-medium">Koreksi</p>
                        </div>
                    </label>
                </div>
                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Jumlah --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span x-text="type === 'adjustment' ? 'Stok Baru' : 'Jumlah'"></span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" name="quantity" min="1" required
                       class="form-input @error('quantity') border-red-500 @enderror"
                       placeholder="Masukkan jumlah">
                @error('quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Referensi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Referensi (opsional)</label>
                <input type="text" name="reference" class="form-input"
                       placeholder="No. PO, No. DO, dll">
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <textarea name="notes" rows="2" class="form-input"
                          placeholder="Keterangan tambahan..."></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1">Simpan Perubahan</button>
                <a href="{{ route('gudang.stocks.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection