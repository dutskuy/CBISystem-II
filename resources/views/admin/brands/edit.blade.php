@extends('layouts.admin')
@section('title', 'Edit Brand')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.brands.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Brand</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Edit Brand: {{ $brand->name }}</h2>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Brand <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}"
                       class="form-input @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Negara Asal</label>
                <input type="text" name="origin_country" value="{{ old('origin_country', $brand->origin_country) }}"
                       class="form-input">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="form-input">{{ old('description', $brand->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Brand</label>
                @if($brand->logo)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}"
                             class="w-16 h-16 object-contain rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500">Logo saat ini. Upload baru untuk mengganti.</p>
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $brand->is_active ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="text-sm font-medium text-gray-700">Brand aktif</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Update Brand</button>
                <a href="{{ route('admin.brands.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection