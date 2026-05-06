@extends('layouts.admin')
@section('title', 'Sesuaikan Stok')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.stocks.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Stok</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Sesuaikan Stok Produk</h2>
    </div>

    {{-- Info Produk --}}
    <div class="card mb-6">
        <div class="flex items-center gap-4">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                     class="w-16 h-16 object-contain rounded-lg border border-gray-100">
            @else
                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            @endif
            <div class="flex-1">
                <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                <p class="text-sm text-gray-500">{{ $product->brand->name }} · SKU: {{ $product->sku }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Stok Saat Ini</p>
                <p class="text-3xl font-bold {{ $product->stock->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                    {{ $product->stock->quantity }}
                </p>
                <p class="text-xs text-gray-400">{{ $product->stock->unit }}</p>
            </div>
        </div>
    </div>

    {{-- Form Penyesuaian --}}
    <div class="card" x-data="{ type: 'in' }">
        <h3 class="font-semibold text-gray-700 mb-5">Form Penyesuaian Stok</h3>

        <form method="POST" action="{{ route('admin.stocks.store', $product) }}" class="space-y-5">
            @csrf

            {{-- Tipe Penyesuaian --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Penyesuaian <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="in" x-model="type" class="sr-only">
                        <div :class="type === 'in' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-600'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="text-sm font-semibold">Stok Masuk</p>
                            <p class="text-xs mt-0.5 opacity-70">Tambah stok</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="out" x-model="type" class="sr-only">
                        <div :class="type === 'out' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-600'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            <p class="text-sm font-semibold">Stok Keluar</p>
                            <p class="text-xs mt-0.5 opacity-70">Kurangi stok</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="adjustment" x-model="type" class="sr-only">
                        <div :class="type === 'adjustment' ? 'border-yellow-500 bg-yellow-50 text-yellow-700' : 'border-gray-200 text-gray-600'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <p class="text-sm font-semibold">Koreksi</p>
                            <p class="text-xs mt-0.5 opacity-70">Set ke nilai</p>
                        </div>
                    </label>
                </div>
                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Jumlah --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span x-text="type === 'adjustment' ? 'Set Stok Ke' : 'Jumlah'"></span>
                    <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" name="quantity" value="{{ old('quantity') }}"
                           class="form-input flex-1 @error('quantity') border-red-500 @enderror"
                           placeholder="Masukkan jumlah..." min="1">
                    <span class="text-gray-500 font-medium">{{ $product->stock->unit }}</span>
                </div>
                @error('quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                {{-- Preview --}}
                <div x-show="type !== 'adjustment'" class="mt-2 text-xs text-gray-500">
                    Stok sesudah:
                    <span x-show="type === 'in'" class="text-green-600 font-semibold">
                        {{ $product->stock->quantity }} + jumlah = hasil baru
                    </span>
                    <span x-show="type === 'out'" class="text-red-600 font-semibold">
                        {{ $product->stock->quantity }} - jumlah = hasil baru
                    </span>
                </div>
            </div>

            {{-- Referensi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Referensi</label>
                <input type="text" name="reference" value="{{ old('reference') }}"
                       class="form-input" placeholder="contoh: PO-2024-001, ADJ-001">
                <p class="mt-1 text-xs text-gray-400">Nomor PO, faktur, atau referensi lainnya (opsional)</p>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="2" class="form-input"
                          placeholder="Alasan penyesuaian stok...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="btn-primary">Simpan Perubahan Stok</button>
                <a href="{{ route('admin.stocks.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    {{-- Riwayat Terakhir --}}
    @php
        $recentMovements = $product->stockMovements()->with('createdBy')->latest()->take(5)->get();
    @endphp
    @if($recentMovements->isNotEmpty())
        <div class="card mt-6">
            <h3 class="font-semibold text-gray-700 mb-4">5 Pergerakan Terakhir</h3>
            <div class="space-y-2">
                @foreach($recentMovements as $mov)
                    <div class="flex items-center justify-between text-sm py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <span class="w-16 text-center text-xs font-bold px-2 py-1 rounded-full
                                {{ $mov->type === 'in' ? 'bg-green-100 text-green-700' : ($mov->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ strtoupper($mov->type) }}
                            </span>
                            <div>
                                <p class="text-gray-700">{{ $mov->notes ?? $mov->reference ?? 'Penyesuaian manual' }}</p>
                                <p class="text-xs text-gray-400">{{ $mov->createdBy->name }} · {{ $mov->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="{{ $mov->type === 'in' ? 'text-green-600' : 'text-red-600' }} font-bold">
                                {{ $mov->type === 'in' ? '+' : ($mov->type === 'out' ? '-' : '=') }}{{ $mov->quantity }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $mov->stock_before }} → {{ $mov->stock_after }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection