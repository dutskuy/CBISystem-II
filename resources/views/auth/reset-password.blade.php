<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Bearindo System</title>
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
            <h1 class="text-white font-bold text-lg">Reset Password</h1>
            <p class="text-blue-300 text-xs mt-1">Buat password baru untuk akun Anda</p>
        </div>

        <div class="px-8 py-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru <span class="text-red-500">*</span>
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
                           placeholder="Ulangi password baru">
                </div>

                <button type="submit"
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>