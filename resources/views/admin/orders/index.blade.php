@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Pending',    'key'=>'pending',    'color'=>'yellow'],
        ['label'=>'Dikonfirmasi','key'=>'confirmed',  'color'=>'blue'],
        ['label'=>'Diproses',   'key'=>'processing', 'color'=>'purple'],
        ['label'=>'Dikirim',    'key'=>'shipped',    'color'=>'indigo'],
    ] as $s)
    <div class="card p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-{{ $s['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <span class="text-{{ $s['color'] }}-700 font-bold text-lg">{{ $summary[$s['key']] }}</span>
        </div>
        <p class="text-sm text-gray-600 font-medium">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold text-gray-800">Manajemen Pesanan</h2>
    <a href="{{ route('admin.payments.index') }}" class="btn-secondary text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        Verifikasi Pembayaran
    </a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari no. pesanan atau nama pelanggan..."
               class="form-input flex-1 min-w-48">
        <select name="status" class="form-input w-40">
            <option value="">Semua Status</option>
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-40">
        <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="form-input w-40">
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','status','date_from','date_to']))
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pembayaran</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono font-medium text-blue-700">
                        <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($order->payment)
                            <span class="badge-{{ $order->payment->status === 'verified' ? 'delivered' : ($order->payment->status === 'rejected' ? 'cancelled' : ($order->payment->status === 'uploaded' ? 'confirmed' : 'pending')) }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Belum bayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $order->created_at->format('d M Y') }}<br>
                        {{ $order->created_at->format('H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection