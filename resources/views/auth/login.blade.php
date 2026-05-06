<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Bearindo System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-blue-900 px-8 py-8 text-center">
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mx-auto mb-4">
                <span class="text-blue-900 font-black text-xl">CBI</span>
            </div>
            <h1 class="text-white font-bold text-xl">PT Central Bearindo International</h1>
            <p class="text-blue-300 text-sm mt-1">Distributor Resmi Bearing & Power Transmission</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-8">
            <h2 class="text-gray-800 font-semibold text-lg mb-6 text-center">Masuk ke Akun Anda</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                               placeholder="email@perusahaan.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                            Ingat saya
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors duration-200">
                        Masuk
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Daftar sekarang</a>
            </p>
        </div>
    </div>

    <p class="text-center text-blue-300 text-xs mt-6">
        © {{ date('Y') }} PT Central Bearindo International. All rights reserved.
    </p>
</div>

</body>
</html>