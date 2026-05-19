@extends('layouts.owner')
@section('title', 'Semua Pesanan')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Semua Pesanan</h1>

    {{-- Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Pesanan</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $summary['pending'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Pending</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $summary['delivered'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Selesai</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-red-500">{{ $summary['cancelled'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Dibatalkan</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari no. pesanan atau nama customer..."
                   class="form-input flex-1 min-w-48">
            <select name="status" class="form-input w-40" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Cari</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('owner.orders.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
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
                        <td class="px-6 py-4 font-mono font-medium text-emerald-700 text-xs">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($order->payment)
                                <span class="badge-{{ $order->payment->status === 'verified' || $order->payment->status === 'manual_verified' ? 'delivered' : ($order->payment->status === 'rejected' ? 'cancelled' : ($order->payment->status === 'uploaded' ? 'confirmed' : 'pending')) }}">
                                    {{ $order->payment->status === 'manual_verified' ? 'Manual' : ucfirst($order->payment->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('owner.orders.show', $order) }}"
                               class="text-emerald-600 hover:underline text-xs font-medium">Detail</a>
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
            <div class="px-6 py-4 border-t">{{ $orders->links() }}</div>
        @endif
    </div>

</div>
@endsection