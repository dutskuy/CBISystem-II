@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Verifikasi Pembayaran</h2>
        <p class="text-sm text-gray-500">Periksa dan verifikasi bukti transfer pelanggan</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <select name="status" class="form-input w-44">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="uploaded" {{ request('status') === 'uploaded' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request('status'))
            <a href="{{ route('admin.payments.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kode Bayar</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pesanan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Bank</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 {{ $payment->status === 'uploaded' ? 'bg-blue-50' : '' }}">
                    <td class="px-6 py-4 font-mono text-xs">{{ $payment->payment_code }}</td>
                    <td class="px-6 py-4 font-mono text-blue-700 text-xs">
                        <a href="{{ route('admin.orders.show', $payment->order) }}">
                            {{ $payment->order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $payment->order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $payment->sender_bank ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-600 text-xs">
                        {{ $payment->bank_name }}<br>
                        <span class="font-mono">{{ $payment->account_number }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $badgeMap = ['pending'=>'pending','uploaded'=>'confirmed','verified'=>'delivered','rejected'=>'cancelled'];
                        @endphp
                        <span class="badge-{{ $badgeMap[$payment->status] }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $payment->order) }}"
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail →</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
    @endif
</div>
@endsection