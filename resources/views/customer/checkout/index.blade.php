@extends('layouts.customer')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

    <form method="POST" action="{{ route('customer.checkout.store') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kiri: Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Alamat Pengiriman --}}
                <div class="card space-y-4">
                    <h3 class="font-semibold text-gray-700 border-b pb-3">Alamat Pengiriman</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="shipping_address" rows="2" required
                                  class="form-input @error('shipping_address') border-red-500 @enderror"
                                  placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan">{{ old('shipping_address', $user->address) }}</textarea>
                        @error('shipping_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kota <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                   class="form-input" placeholder="Jakarta">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_province" value="{{ old('shipping_province') }}" required
                                   class="form-input" placeholder="DKI Jakarta">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                   class="form-input" placeholder="12345">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone', $user->phone) }}" required
                                   class="form-input" placeholder="08123456789">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" class="form-input"
                                  placeholder="Instruksi khusus untuk pengiriman...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="card space-y-4" x-data="{ selectedBank: '{{ $bankAccounts[0]['bank'] }}' }">
                    <h3 class="font-semibold text-gray-700 border-b pb-3">Metode Pembayaran</h3>
                    <p class="text-sm text-gray-500">Transfer Bank Manual ke rekening berikut:</p>

                    @foreach($bankAccounts as $bank)
                        <label class="cursor-pointer">
                            <input type="radio" name="bank_name" value="{{ $bank['bank'] }}" class="sr-only"
                                   x-model="selectedBank">
                            <div :class="selectedBank === '{{ $bank['bank'] }}' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'"
                                 class="border-2 rounded-xl p-4 transition-all">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $bank['bank'] }}</p>
                                        <p class="font-mono text-blue-700 text-lg font-bold">{{ $bank['number'] }}</p>
                                        <p class="text-sm text-gray-500">a/n {{ $bank['name'] }}</p>
                                    </div>
                                    <div :class="selectedBank === '{{ $bank['bank'] }}' ? 'bg-blue-600' : 'bg-gray-200'"
                                         class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                        <svg x-show="selectedBank === '{{ $bank['bank'] }}'" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach

                    {{-- Hidden fields untuk account info --}}
                    @foreach($bankAccounts as $bank)
                        <template x-if="selectedBank === '{{ $bank['bank'] }}'">
                            <div>
                                <input type="hidden" name="account_number" value="{{ $bank['number'] }}">
                                <input type="hidden" name="account_name" value="{{ $bank['name'] }}">
                            </div>
                        </template>
                    @endforeach

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800">
                        <p class="font-semibold mb-1">⚠ Petunjuk Pembayaran:</p>
                        <ol class="list-decimal list-inside space-y-1 text-xs">
                            <li>Transfer sesuai jumlah total pesanan</li>
                            <li>Simpan bukti transfer</li>
                            <li>Upload bukti transfer di halaman detail pesanan</li>
                            <li>Tunggu konfirmasi dari admin (1×24 jam)</li>
                        </ol>
                    </div>
                </div>

            </div>

            {{-- Kanan: Summary --}}
            <div class="h-fit sticky top-24 space-y-4">

                {{-- Item Summary --}}
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Pesanan Anda</h3>
                    <div class="space-y-2 mb-4 max-h-48 overflow-y-auto">
                        @foreach($cart->items as $item)
                            <div class="flex gap-2 text-sm">
                                <div class="w-10 h-10 bg-gray-50 rounded flex-shrink-0 overflow-hidden border border-gray-100">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-contain p-0.5">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-700 text-xs line-clamp-1">{{ $item->product->name }}</p>
                                    <p class="text-gray-400 text-xs">× {{ $item->quantity }}</p>
                                </div>
                                <p class="font-semibold text-xs text-gray-800 flex-shrink-0">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-3 space-y-1 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>PPN 11%</span>
                            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-200">
                            <span>Total</span>
                            <span class="text-blue-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                <button type="submit" class="btn-primary w-full text-base py-3">
                    Buat Pesanan →
                </button>
                <a href="{{ route('customer.cart.index') }}" class="btn-secondary w-full text-center block text-sm">
                    ← Kembali ke Keranjang
                </a>
            </div>

        </div>
    </form>
</div>
@endsection