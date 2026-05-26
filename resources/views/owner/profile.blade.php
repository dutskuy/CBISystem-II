@extends('layouts.owner')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-xl space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>

    {{-- Info Akun --}}
    <div class="card">
        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Informasi Akun</h3>
        <div class="space-y-3 text-sm">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-emerald-700 font-black text-xl">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-base">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded-full mt-1">
                        Owner
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Ganti Password --}}
    <div class="card">
        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Ganti Password</h3>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('owner.profile.password') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password Saat Ini <span class="text-red-500">*</span>
                </label>
                <input type="password" name="current_password" required
                       class="form-input @error('current_password') border-red-500 @enderror"
                       placeholder="Masukkan password saat ini">
                @error('current_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password" name="new_password" required
                       minlength="8"
                       class="form-input @error('new_password') border-red-500 @enderror"
                       placeholder="Min. 8 karakter">
                @error('new_password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password" name="new_password_confirmation" required
                       class="form-input"
                       placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn-primary w-full">
                Simpan Password Baru
            </button>
        </form>
    </div>

</div>
@endsection