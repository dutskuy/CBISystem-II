@extends('layouts.admin')
@section('title', 'Edit Admin')

@section('content')
<div class="max-w-lg">

    <div class="mb-6">
        <a href="{{ route('admin.admins.index') }}" class="text-sm text-blue-600 hover:underline">← Kelola Admin</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">Edit Admin</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.admins.update', $user) }}" class="space-y-4">
            @csrf @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="form-input @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="form-input @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Role <span class="text-red-500">*</span>
                </label>
                <select name="role" required class="form-input">
                    <option value="admin"        {{ old('role', $user->role) === 'admin'        ? 'selected' : '' }}>Admin</option>
                    <option value="admin_gudang" {{ old('role', $user->role) === 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="is_active" class="form-input">
                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.admins.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection