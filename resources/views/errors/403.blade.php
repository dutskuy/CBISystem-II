<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
<div class="text-center">
    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </div>
    <h1 class="text-4xl font-bold text-gray-800 mb-2">403</h1>
    <p class="text-gray-500 mb-6">Anda tidak memiliki akses ke halaman ini.</p>
    <a href="{{ url('/') }}" class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800 transition-colors">
        Kembali ke Beranda
    </a>
</div>
</body>
</html>