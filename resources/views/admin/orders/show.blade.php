@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Pesanan</a>
            <h2 class="text-xl font-bold text-gray-800 mt-1">{{ $order->order_number }}</h2>
            <p class="text-sm text-gray-500">{{ $order->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }}</p>
        </div>
        <span class="badge-{{ $order->status }} text-sm px-3 py-1">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kiri: Items & Timeline --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Item Pesanan --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}"
                                     class="w-12 h-12 object-contain rounded-lg border border-gray-100">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->product->brand->name }} · SKU: {{ $item->product_sku }}</p>
                            </div>
                            <div class="text-right text-sm">
                                <p class="text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                <p class="font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 mt-4 pt-4 space-y-1">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>PPN 11%</span>
                        <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-800 pt-2 border-t border-gray-100">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            @if($order->payment)
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-4 border-b pb-3">Bukti Pembayaran</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div><p class="text-gray-400 text-xs">Kode Pembayaran</p><p class="font-mono font-medium">{{ $order->payment->payment_code }}</p></div>
                        <div><p class="text-gray-400 text-xs">Jumlah Transfer</p><p class="font-bold text-blue-700">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p></div>
                        <div><p class="text-gray-400 text-xs">Bank Tujuan</p><p class="font-medium">{{ $order->payment->bank_name }} - {{ $order->payment->account_number }}</p></div>
                        <div><p class="text-gray-400 text-xs">Pengirim</p><p class="font-medium">{{ $order->payment->sender_name ?? '-' }}</p></div>
                        <div><p class="text-gray-400 text-xs">Bank Pengirim</p><p class="font-medium">{{ $order->payment->sender_bank ?? '-' }}</p></div>
                        <div><p class="text-gray-400 text-xs">Tanggal Transfer</p><p class="font-medium">{{ $order->payment->transfer_date ? $order->payment->transfer_date->format('d M Y') : '-' }}</p></div>
                    </div>
                    @if($order->payment->transfer_proof)
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 mb-2">Bukti Transfer</p>
                            {{-- Thumbnail yang bisa diklik --}}
                            <div class="relative inline-block group cursor-zoom-in"
                                onclick="openLightbox('{{ asset('storage/'.$order->payment->transfer_proof) }}')">
                                <img src="{{ asset('storage/'.$order->payment->transfer_proof) }}"
                                    alt="Bukti Transfer"
                                    class="max-w-xs rounded-lg border border-gray-200 transition-all duration-200 group-hover:brightness-75">
                                {{-- Overlay icon zoom --}}
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="bg-white bg-opacity-90 rounded-full p-3 shadow-lg">
                                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Klik gambar untuk memperbesar</p>
                        </div>
                    @endif
                    {{-- LIGHTBOX MODAL --}}
                    <div id="lightbox"
                        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-90"
                        onclick="closeLightbox()">
                        {{-- Tombol Close --}}
                        <button onclick="closeLightbox()"
                                class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        {{-- Tombol Download --}}
                        <a id="lightbox-download" href="#" download
                        onclick="event.stopPropagation()"
                        class="absolute top-4 left-4 flex items-center gap-2 bg-white text-gray-800 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                        {{-- Gambar --}}
                        <div onclick="event.stopPropagation()" class="max-w-4xl max-h-screen p-4">
                            <img id="lightbox-img" src="" alt="Bukti Transfer"
                                class="max-w-full max-h-screen object-contain rounded-lg shadow-2xl">
                        </div>
                        {{-- Info di bawah --}}
                        <div class="absolute bottom-4 left-0 right-0 text-center text-white text-sm opacity-70">
                            Klik di luar gambar atau tekan <kbd class="bg-white bg-opacity-20 px-1.5 py-0.5 rounded text-xs">ESC</kbd> untuk menutup
                        </div>
                    </div>
                    @if($order->payment->status === 'uploaded')
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.payments.verify', $order->payment) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-success" onclick="return confirm('Verifikasi pembayaran ini?')">
                                    ✓ Verifikasi Pembayaran
                                </button>
                            </form>
                            <button onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                                    class="btn-danger">✗ Tolak</button>
                        </div>
                        <div id="reject-form" class="hidden mt-3">
                            <form method="POST" action="{{ route('admin.payments.reject', $order->payment) }}">
                                @csrf @method('PATCH')
                                <textarea name="rejection_reason" rows="2" required
                                          class="form-input mb-2" placeholder="Alasan penolakan..."></textarea>
                                <button type="submit" class="btn-danger text-sm">Konfirmasi Tolak</button>
                            </form>
                        </div>
                    @elseif($order->payment->status === 'verified')
                        <div class="flex items-center gap-2 text-green-600 text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Diverifikasi oleh {{ $order->payment->verifiedBy->name }} pada {{ $order->payment->verified_at->format('d M Y H:i') }}
                        </div>
                    @elseif($order->payment->status === 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
                            <p class="font-medium">Pembayaran Ditolak</p>
                            <p>{{ $order->payment->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        {{-- Kanan: Info & Aksi --}}
        <div class="space-y-4">

            {{-- Info Pelanggan --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Info Pelanggan</h3>
                <div class="space-y-2 text-sm">
                    <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                    <p class="text-gray-500">{{ $order->user->email }}</p>
                    @if($order->user->phone)
                        <p class="text-gray-500">{{ $order->user->phone }}</p>
                    @endif
                    @if($order->user->company_name)
                        <p class="text-gray-500">{{ $order->user->company_name }}</p>
                    @endif
                </div>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="card">
                <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Alamat Pengiriman</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_province }}</p>
                    <p>{{ $order->shipping_postal_code }}</p>
                    <p class="font-medium text-gray-700">{{ $order->shipping_phone }}</p>
                </div>
                @if($order->notes)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-400">Catatan:</p>
                        <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Update Status --}}
            @if(!in_array($order->status, ['delivered','cancelled']))
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Update Status</h3>
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf @method('PATCH')
                        <select name="status" class="form-input mb-3 text-sm">
                            @php
                                $nextStatuses = [
                                    'pending'    => ['confirmed','cancelled'],
                                    'confirmed'  => ['processing','cancelled'],
                                    'processing' => ['shipped','cancelled'],
                                    'shipped'    => ['delivered'],
                                ];
                                $available = $nextStatuses[$order->status] ?? [];
                            @endphp
                            @foreach($available as $s)
                                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary w-full text-sm"
                                onclick="return confirm('Update status pesanan ini?')">
                            Update Status
                        </button>
                    </form>
                </div>
            @endif

            {{-- Invoice --}}
            @if($order->invoice)
                <div class="card">
                    <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Invoice</h3>
                    <p class="text-sm font-mono text-blue-700">{{ $order->invoice->invoice_number }}</p>
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('admin.invoices.show', $order->invoice) }}"
                           class="btn-secondary text-xs flex-1 text-center">Lihat</a>
                        <a href="{{ route('admin.invoices.download', $order->invoice) }}"
                           class="btn-primary text-xs flex-1 text-center" target="_blank">Download</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
<script>
    function openLightbox(src) {
        const lightbox = document.getElementById('lightbox');
        const img      = document.getElementById('lightbox-img');
        const download = document.getElementById('lightbox-download');

        img.src      = src;
        download.href = src;

        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');

        // Cegah scroll background
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Tutup dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endsection