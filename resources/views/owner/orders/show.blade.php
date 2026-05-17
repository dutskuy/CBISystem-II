@extends('layouts.owner')
@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <a href="{{ route('owner.orders.index') }}" class="text-sm text-emerald-600 hover:underline">← Kembali</a>
        <div class="flex items-center justify-between mt-2">
            <h1 class="text-xl font-bold text-gray-800">{{ $order->order_number }}</h1>
            <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>
        <p class="text-sm text-gray-400">{{ $order->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">

            {{-- Items --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg border flex items-center justify-center flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-contain p-1">
                                @else
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->product_sku }}</p>
                            </div>
                            <div class="text-right text-sm">
                                <p class="text-gray-500 text-xs">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                <p class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t mt-4 pt-4 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>PPN 11%</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 text-base pt-1 border-t">
                        <span>Total</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pembayaran (read only) --}}
            @if($order->payment)
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Info Pembayaran</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><p class="text-xs text-gray-400">Kode</p><p class="font-mono font-medium">{{ $order->payment->payment_code }}</p></div>
                        <div><p class="text-xs text-gray-400">Jumlah</p><p class="font-bold text-blue-700">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p></div>
                        <div><p class="text-xs text-gray-400">Bank Tujuan</p><p class="font-medium">{{ $order->payment->bank_name }}</p></div>
                        <div><p class="text-xs text-gray-400">Status</p>
                            <span class="badge-{{ in_array($order->payment->status, ['verified','manual_verified']) ? 'delivered' : ($order->payment->status === 'rejected' ? 'cancelled' : 'pending') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->payment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="space-y-4">

            {{-- Info Customer --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Customer</h3>
                <p class="font-medium text-gray-800 text-sm">{{ $order->user->name }}</p>
                <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                @if($order->user->company_name)
                    <p class="text-xs text-gray-500 mt-1">{{ $order->user->company_name }}</p>
                @endif
            </div>

            {{-- Alamat --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Alamat Pengiriman</h3>
                <div class="text-xs text-gray-600 space-y-1">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                    <p>{{ $order->shipping_postal_code }}</p>
                    <p class="font-medium">{{ $order->shipping_phone }}</p>
                </div>
            </div>

            {{-- Invoice --}}
            @if($order->invoice)
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Invoice</h3>
                    <p class="font-mono text-xs text-emerald-700 mb-3">{{ $order->invoice->invoice_number }}</p>
                    <a href="{{ route('owner.invoices.download', $order->invoice) }}"
                       class="btn-primary w-full text-center text-xs block" target="_blank">
                        Download Invoice
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection