@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Produk</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Edit Produk</h2>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-6">

            <div class="card space-y-5">
                <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">Informasi Produk</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand <span class="text-red-500">*</span></label>
                        <select name="brand_id" class="form-input">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" class="form-input">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-input font-mono">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Part Number</label>
                        <input type="text" name="part_number" value="{{ old('part_number', $product->part_number) }}" class="form-input font-mono">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Harga Jual (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price"
                            value="{{ old('price', $product->price ?? '') }}"
                            class="form-input @error('price') border-red-500 @enderror"
                            placeholder="contoh: 150000" min="0">
                        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Harga Modal (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="cost_price"
                            value="{{ old('cost_price', $product->cost_price ?? '') }}"
                            class="form-input @error('cost_price') border-red-500 @enderror"
                            placeholder="contoh: 100000" min="0">
                        @error('cost_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                        {{-- Preview margin otomatis --}}
                        <p class="text-xs text-gray-400 mt-1" id="margin-preview">
                            Margin akan dihitung otomatis
                        </p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Teknis</label>
                    <textarea name="specification" rows="4" class="form-input font-mono text-sm">{{ old('specification', $product->specification) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                             class="w-24 h-24 object-contain rounded-lg border border-gray-200 mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ $product->is_active ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Produk aktif</label>
                </div>
            </div>

            <div class="card space-y-4">
                <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">Pengaturan Stok</h3>
                <p class="text-xs text-gray-400">Untuk mengubah jumlah stok, gunakan menu Manajemen Stok.</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', $product->stock?->min_stock ?? 5) }}"
                               class="form-input" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <select name="unit" class="form-input">
                            @foreach(['pcs','box','set','roll','meter'] as $unit)
                                <option value="{{ $unit }}" {{ ($product->stock?->unit ?? 'pcs') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Update Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>

{{-- Script preview margin realtime --}}
<script>
    const price     = document.querySelector('input[name="price"]');
    const costPrice = document.querySelector('input[name="cost_price"]');
    const preview   = document.getElementById('margin-preview');

    function updateMargin() {
        const p = parseFloat(price.value) || 0;
        const c = parseFloat(costPrice.value) || 0;
        if (p > 0 && c > 0) {
            const profit = p - c;
            const margin = ((profit / p) * 100).toFixed(1);
            const color  = margin >= 20 ? 'text-green-600' : margin >= 10 ? 'text-yellow-600' : 'text-red-500';
            preview.className = `text-xs mt-1 font-medium ${color}`;
            preview.textContent = `Margin: ${margin}% (untung Rp ${profit.toLocaleString('id-ID')}/unit)`;
        } else {
            preview.className = 'text-xs text-gray-400 mt-1';
            preview.textContent = 'Margin akan dihitung otomatis';
        }
    }

    price.addEventListener('input', updateMargin);
    costPrice.addEventListener('input', updateMargin);
</script>
@endsection