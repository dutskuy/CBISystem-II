@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Produk</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Tambah Produk Baru</h2>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-6">

            {{-- Info Utama --}}
            <div class="card space-y-5">
                <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">Informasi Produk</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand <span class="text-red-500">*</span></label>
                        <select name="brand_id" class="form-input @error('brand_id') border-red-500 @enderror">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" class="form-input @error('category_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-input @error('name') border-red-500 @enderror"
                           placeholder="contoh: FAG 6205-2Z Deep Groove Ball Bearing">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="form-input font-mono @error('sku') border-red-500 @enderror"
                               placeholder="contoh: FAG-6205-2Z">
                        @error('sku') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Part Number</label>
                        <input type="text" name="part_number" value="{{ old('part_number') }}"
                               class="form-input font-mono" placeholder="contoh: 6205-2Z">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Harga Jual (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" id="price-create"
                            class="form-input @error('price') border-red-500 @enderror"
                            placeholder="contoh: 150000" min="0">
                        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Harga Modal (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="cost_price" value="{{ old('cost_price') }}" id="cost-create"
                            class="form-input @error('cost_price') border-red-500 @enderror"
                            placeholder="contoh: 100000" min="0">
                        @error('cost_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-400 mt-1" id="margin-preview-create">Margin akan dihitung otomatis</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input"
                              placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Teknis</label>
                    <textarea name="specification" rows="4" class="form-input font-mono text-sm"
                              placeholder="Bore: 25mm&#10;OD: 52mm&#10;Width: 15mm&#10;...">{{ old('specification') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                           class="rounded border-gray-300 text-blue-600">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Produk aktif (tampil di katalog)</label>
                </div>
            </div>

            {{-- Stok Awal --}}
            <div class="card space-y-5">
                <h3 class="font-semibold text-gray-700 border-b border-gray-100 pb-3">Stok Awal</h3>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity', 0) }}"
                               class="form-input @error('quantity') border-red-500 @enderror" min="0">
                        @error('quantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}"
                               class="form-input" min="0">
                        <p class="text-xs text-gray-400 mt-1">Alert jika stok ≤ nilai ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <select name="unit" class="form-input">
                            <option value="pcs" {{ old('unit') === 'pcs' ? 'selected' : '' }}>pcs</option>
                            <option value="box" {{ old('unit') === 'box' ? 'selected' : '' }}>box</option>
                            <option value="set" {{ old('unit') === 'set' ? 'selected' : '' }}>set</option>
                            <option value="roll" {{ old('unit') === 'roll' ? 'selected' : '' }}>roll</option>
                            <option value="meter" {{ old('unit') === 'meter' ? 'selected' : '' }}>meter</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Batal</a>
            </div>

        </div>
    </form>
</div>

<script>
    const price     = document.getElementById('price-create');
    const costPrice = document.getElementById('cost-create');
    const preview   = document.getElementById('margin-preview-create');

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