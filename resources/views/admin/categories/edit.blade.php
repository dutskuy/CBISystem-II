@extends('layouts.admin')
@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Edit Kategori: {{ $category->name }}</h2>
    </div>
    <div class="card">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="form-input @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="form-input">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                @if($category->image)
                    <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}"
                         class="w-20 h-20 object-cover rounded-lg border border-gray-200 mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $category->is_active ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="text-sm font-medium text-gray-700">Kategori aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Update Kategori</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection