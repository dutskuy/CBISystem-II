@extends('layouts.customer')
@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h1>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <form method="GET" class="flex gap-3">
            <select name="status" class="form-input flex-1" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            @if(request('status'))
                <a href="{{ route('customer.orders.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="card text-center py-16">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="text-gray-500">Belum ada pesanan.</p>
            <a href="{{ route('customer.products.index') }}" class="btn-primary inline-block mt-4">Mulai Belanja</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card p-0 overflow-hidden">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 bg-gray-50">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="font-mono font-semibold text-blue-700 text-sm">{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y · H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            @if($order->payment)
                                @php $pm = ['pending'=>'pending','uploaded'=>'confirmed','verified'=>'delivered','rejected'=>'cancelled']; @endphp
                                <span class="badge-{{ $pm[$order->payment->status] ?? 'pending' }} text-xs">
                                    Bayar: {{ ucfirst($order->payment->status) }}
                                </span>
                            @endif
                            @if($order->status === 'pending' && $order->payment?->status === 'pending')
                                @php
                                    $expiredAt = $order->created_at->addMinutes(config('bearindo.order_expiry_minutes', 10));
                                    $remaining = now()->diffInSeconds($expiredAt, false);
                                @endphp
                                @if($remaining > 0)
                                    <span class="text-xs text-orange-500 font-medium bg-orange-50 px-2 py-1 rounded-full border border-orange-200">
                                        ⏳ Expires {{ $expiredAt->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-xs text-red-500 font-medium bg-red-50 px-2 py-1 rounded-full border border-red-200">
                                        ⚠ Expired
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Items preview --}}
                    <div class="px-6 py-4">
                        <div class="flex gap-2 mb-3">
                            @foreach($order->items->take(3) as $item)
                                <div class="w-12 h-12 bg-gray-50 rounded-lg border border-gray-100 overflow-hidden flex-shrink-0">
                                    @if($item->product->image ?? false)
                                        <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-contain p-0.5">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-500 font-medium">
                                    +{{ $order->items->count() - 3 }}
                                </div>
                            @endif
                            <div class="flex-1 ml-2">
                                <p class="text-sm text-gray-700">{{ $order->items->first()->product_name }}</p>
                                @if($order->items->count() > 1)
                                    <p class="text-xs text-gray-400">+{{ $order->items->count()-1 }} produk lainnya</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-400">Total Pembayaran</p>
                                <p class="font-bold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex gap-2">
                                @if($order->status === 'pending' && $order->payment?->status === 'pending')
                                    <a href="{{ route('customer.orders.show', $order) }}"
                                       class="btn-primary text-sm py-1.5">Upload Bukti Bayar</a>
                                @elseif($order->payment?->status === 'rejected')
                                    <a href="{{ route('customer.orders.show', $order) }}"
                                       class="btn-danger text-sm py-1.5">Upload Ulang</a>
                                @else
                                    <a href="{{ route('customer.orders.show', $order) }}"
                                       class="btn-secondary text-sm py-1.5">Lihat Detail</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</div>
@endsection