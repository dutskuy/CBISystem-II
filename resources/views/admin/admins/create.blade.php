@extends('layouts.admin')
@section('title', 'Tambah Admin')

@section('content')
<div class="max-w-lg">

    <div class="mb-6">
        <a href="{{ route('admin.admins.index') }}" class="text-sm text-blue-600 hover:underline">← Kelola Admin</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">Tambah Admin Baru</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="form-input @error('name') border-red-500 @enderror"
                       placeholder="Nama admin">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="form-input @error('email') border-red-500 @enderror"
                       placeholder="email@bearindo.com">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Role <span class="text-red-500">*</span>
                </label>
                <select name="role" required class="form-input @error('role') border-red-500 @enderror">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin"        {{ old('role') === 'admin'        ? 'selected' : '' }}>Admin</option>
                    <option value="admin_gudang" {{ old('role') === 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                {{-- Deskripsi role --}}
                <div class="mt-2 p-3 bg-blue-50 rounded-lg text-xs text-blue-700 space-y-1">
                    <p><strong>Admin</strong> — Akses penuh operasional (produk, stok, pesanan, pembayaran, laporan). Tidak bisa lihat harga modal.</p>
                    <p><strong>Admin Gudang</strong> — Hanya akses manajemen stok & produk.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required minlength="8"
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="Min. 8 karakter">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="form-input" placeholder="Ulangi password">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Buat Akun Admin</button>
                <a href="{{ route('admin.admins.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection