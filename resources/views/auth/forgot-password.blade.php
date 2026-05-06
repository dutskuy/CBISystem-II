<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Bearindo System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-blue-900 px-8 py-6 text-center">
            <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-blue-900 font-black text-lg">CBI</span>
            </div>
            <h1 class="text-white font-bold text-lg">Lupa Password</h1>
            <p class="text-blue-300 text-xs mt-1">Masukkan email untuk reset password</p>
        </div>

        <div class="px-8 py-6">

            @if(session('status'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4 text-sm text-green-700">
                    <p class="font-semibold mb-1">✓ Email Terkirim!</p>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <p class="text-sm text-gray-600 mb-4">
                Masukkan alamat email yang terdaftar. Kami akan mengirimkan link untuk reset password Anda.
            </p>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="email@perusahaan.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors">
                    Kirim Link Reset Password
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Ingat password?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>