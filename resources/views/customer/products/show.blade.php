@extends('layouts.customer')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('customer.products.index') }}" class="hover:text-blue-600">Produk</a>
        <span>/</span>
        <a href="{{ route('customer.products.index', ['brand_id' => $product->brand_id]) }}" class="hover:text-blue-600">{{ $product->brand->name }}</a>
        <span>/</span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">

        {{-- Foto --}}
        <div>
            <div class="aspect-square bg-gray-50 rounded-2xl flex items-center justify-center overflow-hidden border border-gray-100">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-contain p-4">
                @else
                    <svg class="w-24 h-24 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-sm font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ $product->brand->name }}</span>
                <span class="text-sm text-gray-400">{{ $product->category->name }}</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                <span>SKU: <span class="font-mono font-medium text-gray-700">{{ $product->sku }}</span></span>
                @if($product->part_number)
                    <span>·</span>
                    <span>Part No: <span class="font-mono font-medium text-gray-700">{{ $product->part_number }}</span></span>
                @endif
            </div>

            <p class="text-3xl font-bold text-blue-900 mb-4">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            {{-- Stok --}}
            @if($product->stock)
                @if($product->stock->quantity > 0)
                    <div class="flex items-center gap-2 text-green-600 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Stok Tersedia: {{ $product->stock->quantity }} {{ $product->stock->unit }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-red-500 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Stok Habis</span>
                    </div>
                @endif
            @endif

            {{-- Deskripsi --}}
            @if($product->description)
                <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $product->description }}</p>
            @endif

            {{-- Add to Cart --}}
            @if($product->stock?->quantity > 0)
                <form method="POST" action="{{ route('customer.cart.add') }}" class="flex gap-3 mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                        <button type="button" onclick="changeQty(-1)"
                                class="px-3 py-2.5 text-gray-600 hover:bg-gray-100 font-bold">−</button>
                        <input type="number" name="quantity" id="qty" value="1"
                               min="1" max="{{ $product->stock->quantity }}"
                               class="w-14 text-center border-x border-gray-300 py-2.5 text-sm font-medium focus:outline-none">
                        <button type="button" onclick="changeQty(1)"
                                class="px-3 py-2.5 text-gray-600 hover:bg-gray-100 font-bold">+</button>
                    </div>
                    <button type="submit" class="btn-primary flex-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Tambah ke Keranjang
                    </button>
                </form>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-400 font-semibold py-3 rounded-lg cursor-not-allowed mb-4">
                    Stok Habis
                </button>
            @endif
        </div>
    </div>

    {{-- Spesifikasi --}}
    @if($product->specification)
        <div class="card mb-8">
            <h2 class="font-semibold text-gray-700 mb-4 border-b pb-3">Spesifikasi Teknis</h2>
            <pre class="text-sm text-gray-600 font-mono whitespace-pre-wrap bg-gray-50 p-4 rounded-lg">{{ $product->specification }}</pre>
        </div>
    @endif

    {{-- Produk Terkait --}}
    @if($related->isNotEmpty())
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Produk Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($related as $rel)
                    <a href="{{ route('customer.products.show', $rel) }}"
                       class="card p-3 hover:shadow-md transition-all group block">
                        <div class="aspect-square bg-gray-50 rounded-lg mb-3 overflow-hidden flex items-center justify-center">
                            @if($rel->image)
                                <img src="{{ asset('storage/'.$rel->image) }}" alt="{{ $rel->name }}"
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-10 h-10 text-gray-200">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <span class="text-xs font-semibold text-blue-600">{{ $rel->brand->name }}</span>
                        <p class="text-sm font-medium text-gray-800 mt-0.5 line-clamp-2">{{ $rel->name }}</p>
                        <p class="text-sm font-bold text-blue-900 mt-1">Rp {{ number_format($rel->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const max   = parseInt(input.max);
    let val     = parseInt(input.value) + delta;
    input.value = Math.min(Math.max(1, val), max);
}
</script>
@endsection