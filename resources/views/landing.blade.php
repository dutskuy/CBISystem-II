<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Central Bearindo International — Distributor Bearing & Power Transmission</title>
    <meta name="description" content="Distributor resmi bearing dan power transmission terpercaya di Indonesia sejak 1994.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased">

    {{-- ===== NAVBAR ===== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-900 rounded-lg flex items-center justify-center">
                        <span class="text-white font-black text-sm">CBI</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="font-bold text-gray-800 text-sm leading-tight">PT Central Bearindo</p>
                        <p class="text-xs text-gray-400 leading-tight">International</p>
                    </div>
                </a>

                {{-- Nav Links --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#tentang"  class="text-sm text-gray-600 hover:text-blue-700 transition-colors">Tentang</a>
                    <a href="#produk"   class="text-sm text-gray-600 hover:text-blue-700 transition-colors">Produk</a>
                    <a href="#brand"    class="text-sm text-gray-600 hover:text-blue-700 transition-colors">Brand</a>
                    <a href="#kontak"   class="text-sm text-gray-600 hover:text-blue-700 transition-colors">Kontak</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-blue-700 hover:text-blue-900 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-medium bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg transition-colors">
                        Daftar
                    </a>

                    {{-- Mobile Menu Button --}}
                    <button @click="open = !open" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="open" x-transition class="md:hidden border-t border-gray-100 py-3 space-y-1">
                <a href="#tentang" @click="open=false" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Tentang</a>
                <a href="#produk"  @click="open=false" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Produk</a>
                <a href="#brand"   @click="open=false" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Brand</a>
                <a href="#kontak"  @click="open=false" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">Kontak</a>
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="min-h-screen bg-gradient-to-br from-blue-950 via-blue-900 to-blue-800 flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Text --}}
                <div>
                    <div class="inline-flex items-center gap-2 bg-blue-800 bg-opacity-60 text-blue-200 text-xs font-medium px-3 py-1.5 rounded-full mb-6">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        Distributor Resmi Sejak 1994
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight mb-6">
                        Solusi Bearing &
                        <span class="text-yellow-400">Power Transmission</span>
                        Terpercaya
                    </h1>
                    <p class="text-blue-200 text-lg leading-relaxed mb-8">
                        PT Central Bearindo International adalah distributor resmi bearing dan komponen
                        power transmission berkualitas tinggi untuk industri di seluruh Indonesia.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}"
                           class="bg-yellow-400 hover:bg-yellow-300 text-blue-900 font-bold px-6 py-3 rounded-xl transition-colors text-sm">
                            Daftar & Mulai Belanja →
                        </a>
                        <a href="#produk"
                           class="bg-white bg-opacity-10 hover:bg-opacity-20 text-white font-medium px-6 py-3 rounded-xl transition-colors text-sm border border-white border-opacity-20">
                            Lihat Produk
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-blue-800">
                        <div>
                            <p class="text-3xl font-black text-white">30+</p>
                            <p class="text-blue-300 text-xs mt-1">Tahun Pengalaman</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-white">{{ $brands->count() }}+</p>
                            <p class="text-blue-300 text-xs mt-1">Brand Ternama</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-white">1000+</p>
                            <p class="text-blue-300 text-xs mt-1">Produk Tersedia</p>
                        </div>
                    </div>
                </div>

                {{-- Visual --}}
                <div class="hidden lg:flex justify-center">
                    <div class="relative">
                        {{-- Lingkaran dekoratif --}}
                        <div class="w-80 h-80 rounded-full bg-blue-800 bg-opacity-40 flex items-center justify-center">
                            <div class="w-60 h-60 rounded-full bg-blue-700 bg-opacity-40 flex items-center justify-center">
                                <div class="w-40 h-40 rounded-full bg-yellow-400 bg-opacity-20 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        {{-- Badge floating --}}
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-3 text-center">
                            <p class="text-2xl font-black text-blue-900">FAG</p>
                            <p class="text-xs text-gray-400">Germany</p>
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-3 text-center">
                            <p class="text-2xl font-black text-blue-900">NSK</p>
                            <p class="text-xs text-gray-400">Japan</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===== TENTANG ===== --}}
    <section id="tentang" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-800 mb-4">Mengapa Memilih Bearindo?</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Kami berkomitmen memberikan produk berkualitas tinggi dengan layanan terbaik untuk kebutuhan industri Anda.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Produk Original', 'desc' => 'Semua produk 100% original langsung dari distributor resmi, bergaransi keaslian.'],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Pengiriman Cepat', 'desc' => 'Proses order cepat dan pengiriman ke seluruh wilayah Indonesia.'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Tim Berpengalaman', 'desc' => 'Tim teknis kami siap membantu memilih produk yang tepat untuk kebutuhan Anda.'],
                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Harga Kompetitif', 'desc' => 'Harga terbaik untuk kualitas premium, dengan berbagai pilihan metode pembayaran.'],
                ] as $item)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== BRAND ===== --}}
    <section id="brand" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-800 mb-4">Brand yang Kami Distribusikan</h2>
                <p class="text-gray-500">Kami adalah distributor resmi brand-brand bearing terkemuka dunia.</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($brands as $brand)
                    <div class="border border-gray-100 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:border-blue-200 transition-all group">
                        @if($brand->logo)
                            <img src="{{ asset('storage/'.$brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="h-10 object-contain grayscale group-hover:grayscale-0 transition-all">
                        @else
                            <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center">
                                <span class="text-white font-black text-xs">{{ strtoupper(substr($brand->name, 0, 3)) }}</span>
                            </div>
                        @endif
                        <p class="text-xs font-semibold text-gray-600 text-center">{{ $brand->name }}</p>
                        @if($brand->origin_country)
                            <p class="text-xs text-gray-400">{{ $brand->origin_country }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== PRODUK ===== --}}
    <section id="produk" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 mb-2">Produk Terbaru</h2>
                    <p class="text-gray-500">Temukan produk bearing dan power transmission terlengkap.</p>
                </div>
                <a href="{{ route('login') }}"
                   class="hidden sm:flex items-center gap-2 text-sm font-medium text-blue-700 hover:text-blue-900">
                    Lihat Semua →
                </a>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p>Belum ada produk tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 hover:shadow-md transition-all group">
                            <div class="aspect-square bg-gray-50 rounded-xl mb-3 overflow-hidden flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-blue-600">{{ $product->brand->name }}</span>
                            <p class="text-sm font-semibold text-gray-800 mt-1 line-clamp-2 leading-snug">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono mt-1">{{ $product->sku }}</p>
                            <p class="text-base font-black text-blue-900 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <a href="{{ route('register') }}"
                               class="mt-3 w-full block text-center text-xs font-medium bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg transition-colors">
                                Daftar untuk Beli
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="py-20 bg-blue-900">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-black text-white mb-4">Siap Memulai?</h2>
            <p class="text-blue-200 mb-8 text-lg">Daftar sekarang dan dapatkan akses ke ribuan produk bearing & power transmission berkualitas.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}"
                   class="bg-yellow-400 hover:bg-yellow-300 text-blue-900 font-bold px-8 py-3.5 rounded-xl transition-colors">
                    Daftar Sekarang — Gratis
                </a>
                <a href="{{ route('login') }}"
                   class="bg-white bg-opacity-10 hover:bg-opacity-20 text-white font-medium px-8 py-3.5 rounded-xl transition-colors border border-white border-opacity-20">
                    Sudah Punya Akun? Masuk
                </a>
            </div>
        </div>
    </section>

    {{-- ===== KONTAK ===== --}}
    <section id="kontak" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-800 mb-4">Hubungi Kami</h2>
                <p class="text-gray-500">Ada pertanyaan? Tim kami siap membantu Anda.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
                @foreach([
                    ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => 'Telepon', 'value' => '+62 21 1234 5678'],
                    ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Email', 'value' => 'info@bearindo.com'],
                    ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Alamat', 'value' => 'Jakarta, Indonesia'],
                ] as $kontak)
                    <div class="text-center p-6 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kontak['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400 mb-1">{{ $kontak['label'] }}</p>
                        <p class="font-semibold text-gray-700 text-sm">{{ $kontak['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-blue-950 text-blue-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-blue-900 font-black text-xs">CBI</span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">PT Central Bearindo International</p>
                        <p class="text-blue-400 text-xs">Distributor Resmi Bearing & Power Transmission</p>
                    </div>
                </div>
                <p class="text-xs text-blue-400">© {{ date('Y') }} PT Central Bearindo International. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>