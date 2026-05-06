@extends('layouts.customer')
@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('customer.orders.index') }}" class="text-sm text-blue-600 hover:underline">← Riwayat Pesanan</a>
        <div class="flex items-center justify-between mt-2">
            <h1 class="text-xl font-bold text-gray-800">{{ $order->order_number }}</h1>
            <span class="badge-{{ $order->status }} text-sm px-3 py-1">{{ ucfirst($order->status) }}</span>
        </div>
        <p class="text-sm text-gray-400">{{ $order->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kiri --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Items --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 bg-gray-50 rounded-lg border border-gray-100 overflow-hidden flex-shrink-0 flex items-center justify-center">
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
                                <p class="text-xs text-gray-400">{{ $item->product->brand->name }} · {{ $item->product_sku }}</p>
                                <p class="text-xs text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t mt-4 pt-4 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->tax > 0)
                        <div class="flex justify-between text-gray-500">
                            <span>PPN 11%</span>
                            <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @if($order->payment && $order->payment->status === 'pending' && $order->status === 'pending')
                @php
                    $expiredAt = $order->created_at->addMinutes(config('bearindo.order_expiry_minutes', 10));
                    $remaining = now()->diffInSeconds($expiredAt, false);
                @endphp

                @if($remaining > 0)
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-orange-700 font-semibold text-sm">Segera lakukan pembayaran!</p>
                        </div>
                        <p class="text-xs text-orange-600 mb-3">
                            Pesanan akan otomatis dibatalkan jika pembayaran tidak diterima dalam waktu:
                        </p>
                        <div class="flex gap-3 justify-center" id="countdown">
                            <div class="text-center bg-orange-100 rounded-lg px-4 py-2 min-w-16">
                                <p class="text-2xl font-bold text-orange-800" id="countdown-minutes">00</p>
                                <p class="text-xs text-orange-500">Menit</p>
                            </div>
                            <div class="text-center bg-orange-100 rounded-lg px-4 py-2 min-w-16">
                                <p class="text-2xl font-bold text-orange-800" id="countdown-seconds">00</p>
                                <p class="text-xs text-orange-500">Detik</p>
                            </div>
                        </div>
                    </div>

                    <script>
                        let remaining = {{ $remaining }};

                        function updateCountdown() {
                            if (remaining <= 0) {
                                document.getElementById('countdown').innerHTML =
                                    '<p class="text-red-600 font-semibold text-sm w-full text-center">⚠ Waktu habis! Pesanan akan segera dibatalkan.</p>';
                                setTimeout(() => location.reload(), 5000);
                                return;
                            }

                            const minutes = Math.floor(remaining / 60);
                            const seconds = remaining % 60;

                            document.getElementById('countdown-minutes').textContent =
                                String(minutes).padStart(2, '0');
                            document.getElementById('countdown-seconds').textContent =
                                String(seconds).padStart(2, '0');

                            // Warna berubah merah saat < 2 menit
                            if (remaining < 120) {
                                document.querySelectorAll('#countdown .bg-orange-100').forEach(el => {
                                    el.classList.replace('bg-orange-100', 'bg-red-100');
                                });
                                document.querySelectorAll('#countdown .text-orange-800').forEach(el => {
                                    el.classList.replace('text-orange-800', 'text-red-700');
                                });
                            }

                            remaining--;
                        }

                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    </script>

                @else
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <p class="text-red-600 font-semibold text-sm">⚠ Waktu pembayaran telah habis.</p>
                        <p class="text-xs text-red-500 mt-1">Pesanan akan segera dibatalkan oleh sistem.</p>
                    </div>
                @endif
@endif
            {{-- Upload Bukti Pembayaran --}}
            @if($order->payment)
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Pembayaran</h3>

                    {{-- Info Rekening Tujuan --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                        <p class="text-xs text-blue-500 font-semibold uppercase mb-1">Transfer ke:</p>
                        <p class="text-lg font-bold text-blue-900">{{ $order->payment->bank_name }}</p>
                        <p class="text-2xl font-bold text-blue-700 font-mono">{{ $order->payment->account_number }}</p>
                        <p class="text-sm text-blue-700">a/n {{ $order->payment->account_name }}</p>
                        <div class="border-t border-blue-200 mt-3 pt-3">
                            <p class="text-xs text-blue-500">Jumlah Transfer:</p>
                            <p class="text-xl font-bold text-blue-900">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Status Pembayaran --}}
                    @if($order->payment->status === 'verified')
                        <div class="flex items-center gap-2 text-green-600 bg-green-50 rounded-lg p-3 mb-4">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm font-medium">Pembayaran telah diverifikasi!</p>
                        </div>
                    @elseif($order->payment->status === 'uploaded')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4 text-sm text-yellow-800">
                            <p class="font-semibold">⏳ Menunggu Verifikasi Admin</p>
                            <p class="text-xs mt-1">Bukti transfer Anda sedang diperiksa. Proses 1×24 jam.</p>
                        </div>
                    @elseif($order->payment->status === 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-sm text-red-700">
                            <p class="font-semibold">✗ Pembayaran Ditolak</p>
                            <p class="text-xs mt-1">{{ $order->payment->rejection_reason }}</p>
                            <p class="text-xs mt-1">Silakan upload ulang bukti transfer yang benar.</p>
                        </div>
                    @endif

                    {{-- Form Upload --}}
                    @if(in_array($order->payment->status, ['pending', 'rejected']))
                        <form method="POST" action="{{ route('customer.orders.pay', $order) }}"
                              enctype="multipart/form-data" class="space-y-4 border-t pt-4">
                            @csrf
                            <h4 class="font-medium text-gray-700">Upload Bukti Transfer</h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer <span class="text-red-500">*</span></label>
                                <input type="file" name="transfer_proof" accept="image/*" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                                <p class="text-xs text-gray-400 mt-1">Format JPG/PNG, maks 5MB</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengirim <span class="text-red-500">*</span></label>
                                    <input type="text" name="sender_name" required class="form-input"
                                           placeholder="Nama sesuai rekening">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Pengirim <span class="text-red-500">*</span></label>
                                    <input type="text" name="sender_bank" required class="form-input"
                                           placeholder="BCA, Mandiri, BNI...">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transfer <span class="text-red-500">*</span></label>
                                <input type="date" name="transfer_date" required class="form-input"
                                       max="{{ date('Y-m-d') }}">
                            </div>

                            <button type="submit" class="btn-primary w-full">Upload Bukti Transfer</button>
                        </form>
                    @elseif($order->payment->transfer_proof)
                        <div class="border-t pt-4">
                            <p class="text-xs text-gray-400 mb-2">Bukti Transfer:</p>
                            <img src="{{ asset('storage/'.$order->payment->transfer_proof) }}"
                                 class="max-w-xs rounded-lg border border-gray-200">
                        </div>
                    @endif
                </div>
            @endif

        </div>

        {{-- Kanan --}}
        <div class="space-y-4">
            {{-- Alamat --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Alamat Pengiriman</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                    <p>{{ $order->shipping_postal_code }}</p>
                    <p class="font-medium">{{ $order->shipping_phone }}</p>
                </div>
            </div>

            {{-- Batalkan --}}
            @if($order->status === 'pending')
                <div class="card">
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-danger w-full text-sm"
                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Invoice --}}
            @if($order->invoice)
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Invoice</h3>
                    <p class="text-sm font-mono text-blue-700 mb-3">{{ $order->invoice->invoice_number }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('customer.invoices.show', $order->invoice) }}"
                           class="btn-secondary text-xs flex-1 text-center">Lihat</a>
                        <a href="{{ route('customer.invoices.download', $order->invoice) }}"
                           class="btn-primary text-xs flex-1 text-center" target="_blank">Download</a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection