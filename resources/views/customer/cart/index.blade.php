@extends('layouts.customer')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>

    @if(!$cart || $cart->items->isEmpty())
        <div class="card text-center py-16">
            <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-gray-500 font-medium text-lg mb-2">Keranjang Anda kosong</p>
            <p class="text-gray-400 text-sm mb-6">Mulai belanja dan temukan produk bearing terbaik</p>
            <a href="{{ route('customer.products.index') }}" class="btn-primary inline-block">Mulai Belanja</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Items --}}
            <div class="lg:col-span-2 space-y-3">
                @foreach($cart->items as $item)
                    <div class="card flex gap-4 p-4">
                        {{-- Gambar --}}
                        <a href="{{ route('customer.products.show', $item->product) }}"
                           class="w-20 h-20 flex-shrink-0 bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center border border-gray-100">
                            @if($item->product->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}"
                                     alt="{{ $item->product->name }}" class="w-full h-full object-contain p-1">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <span class="text-xs font-semibold text-blue-600">{{ $item->product->brand->name }}</span>
                            <p class="font-medium text-gray-800 text-sm line-clamp-2">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $item->product->sku }}</p>
                            <p class="text-sm font-bold text-blue-900 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                            {{-- Stok warning --}}
                            @if($item->product->stock && $item->quantity > $item->product->stock->quantity)
                                <p class="text-xs text-red-500 mt-1">⚠ Stok tidak mencukupi</p>
                            @endif
                        </div>

                        {{-- Qty & Hapus --}}
                        <div class="flex flex-col items-end justify-between flex-shrink-0">
                            <form method="POST" action="{{ route('customer.cart.remove', $item) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>

                            <div>
                                <form method="POST" action="{{ route('customer.cart.update', $item) }}" class="flex items-center gap-1">
                                    @csrf @method('PATCH')
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}"
                                                class="px-2 py-1 text-gray-500 hover:bg-gray-100 text-sm font-bold">−</button>
                                        <span class="px-3 py-1 text-sm font-semibold border-x border-gray-200">{{ $item->quantity }}</span>
                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                class="px-2 py-1 text-gray-500 hover:bg-gray-100 text-sm font-bold">+</button>
                                    </div>
                                </form>
                                <p class="text-right text-sm font-bold text-gray-800 mt-1">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Kosongkan --}}
                <form method="POST" action="{{ route('customer.cart.clear') }}" class="text-right">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Kosongkan semua keranjang?')"
                            class="text-sm text-red-500 hover:text-red-700">Kosongkan Keranjang</button>
                </form>
            </div>

            {{-- Summary --}}
            <div class="card h-fit sticky top-24">
                <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Ringkasan Pesanan</h3>
                <div class="space-y-2 text-sm mb-4">
                @php
                    $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
                    $tax      = round($subtotal * config('bearindo.tax_rate'));
                    $total    = $subtotal + $tax;
                @endphp
                <div class="flex justify-between text-gray-600">
                    <span>{{ $cart->items->sum('quantity') }} item</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>PPN 11%</span>
                    <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex justify-between font-bold text-gray-800 text-base border-t pt-3 mb-5">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
                <a href="{{ route('customer.checkout.index') }}"
                   class="btn-primary w-full text-center block">
                    Lanjut ke Checkout →
                </a>
                <a href="{{ route('customer.products.index') }}"
                   class="btn-secondary w-full text-center block mt-2 text-sm">
                    Lanjut Belanja
                </a>
            </div>

        </div>
    @endif
</div>
@endsection