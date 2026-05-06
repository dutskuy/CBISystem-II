<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Bearindo System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 flex items-center justify-center p-4">

<div class="w-full max-w-lg">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-blue-900 px-8 py-6 text-center">
            <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-blue-900 font-black text-lg">CBI</span>
            </div>
            <h1 class="text-white font-bold text-lg">PT Central Bearindo International</h1>
            <p class="text-blue-300 text-xs mt-1">Buat akun untuk mulai berbelanja</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-6">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Nama lengkap Anda">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="email@perusahaan.com">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Telepon & Perusahaan --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                               placeholder="08123456789">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="PT / CV / UD ...">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Alamat lengkap (opsional)">{{ old('address') }}</textarea>
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="Min. 8 karakter">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors duration-200 mt-2">
                    Buat Akun
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Masuk di sini</a>
            </p>
        </div>
    </div>

    <p class="text-center text-blue-300 text-xs mt-4">
        © {{ date('Y') }} PT Central Bearindo International. All rights reserved.
    </p>
</div>

</body>
</html>