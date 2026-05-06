@extends('layouts.customer')
@section('title', 'Katalog Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $products->total() }} produk tersedia</p>
    </div>

    <div class="flex gap-6">

        {{-- Sidebar Filter --}}
        <aside class="w-56 flex-shrink-0 hidden lg:block">
            <form method="GET" id="filterForm">
                <div class="card space-y-5">

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Brand</label>
                        <div class="space-y-2">
                            @foreach($brands as $brand)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="brand_id" value="{{ $brand->id }}"
                                           {{ request('brand_id') == $brand->id ? 'checked' : '' }}
                                           class="text-blue-600" onchange="this.form.submit()">
                                    <span class="text-sm text-gray-700">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                            @if(request('brand_id'))
                                <a href="{{ request()->fullUrlWithoutQuery(['brand_id']) }}"
                                   class="text-xs text-blue-600 hover:underline">Hapus filter brand</a>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Kategori</label>
                        <div class="space-y-2">
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="category_id" value="{{ $cat->id }}"
                                           {{ request('category_id') == $cat->id ? 'checked' : '' }}
                                           class="text-blue-600" onchange="this.form.submit()">
                                    <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                            @if(request('category_id'))
                                <a href="{{ request()->fullUrlWithoutQuery(['category_id']) }}"
                                   class="text-xs text-blue-600 hover:underline">Hapus filter kategori</a>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Harga (Rp)</label>
                        <div class="space-y-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                   placeholder="Min" class="form-input text-sm">
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   placeholder="Max" class="form-input text-sm">
                            <button type="submit" class="btn-primary w-full text-sm py-1.5">Terapkan</button>
                        </div>
                    </div>

                    @if(request()->hasAny(['brand_id','category_id','min_price','max_price','search']))
                        <a href="{{ route('customer.products.index') }}"
                           class="block text-center text-sm text-red-600 hover:underline border-t pt-3">
                            Reset Semua Filter
                        </a>
                    @endif

                    {{-- Hidden fields untuk search & sort --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                </div>
            </form>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1">

            {{-- Search & Sort --}}
            <div class="flex gap-3 mb-4">
                <form method="GET" class="flex-1 flex gap-2">
                    @foreach(request()->except('search','page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, SKU, atau part number..."
                           class="form-input flex-1 text-sm">
                    <button type="submit" class="btn-primary text-sm px-4">Cari</button>
                </form>
                <form method="GET">
                    @foreach(request()->except('sort','page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <select name="sort" class="form-input text-sm" onchange="this.form.submit()">
                        <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc'           ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc'          ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="name_asc"   {{ request('sort') === 'name_asc'            ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </form>
            </div>

            {{-- Product Grid --}}
            @if($products->isEmpty())
                <div class="card text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Produk tidak ditemukan</p>
                    <a href="{{ route('customer.products.index') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">Reset filter</a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <a href="{{ route('customer.products.show', $product) }}"
                           class="card p-3 hover:shadow-md transition-all group block">
                            <div class="aspect-square bg-gray-50 rounded-lg mb-3 overflow-hidden flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-blue-600">{{ $product->brand->name }}</span>
                                <p class="text-sm font-medium text-gray-800 mt-0.5 line-clamp-2 leading-snug">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400 font-mono mt-1">{{ $product->sku }}</p>
                                <p class="text-base font-bold text-blue-900 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @if($product->stock)
                                    @if($product->stock->quantity > 0)
                                        <p class="text-xs text-green-600 mt-1">✓ Stok: {{ $product->stock->quantity }} {{ $product->stock->unit }}</p>
                                    @else
                                        <p class="text-xs text-red-500 mt-1">✗ Stok habis</p>
                                    @endif
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection