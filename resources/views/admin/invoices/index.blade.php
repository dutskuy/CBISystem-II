@extends('layouts.admin')
@section('title', 'Invoice')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Invoice</h2>
        <p class="text-sm text-gray-500">Semua invoice yang telah dibuat</p>
    </div>
</div>

<div class="card mb-4">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nomor invoice atau nama pelanggan..."
               class="form-input flex-1">
        <select name="status" class="form-input w-36">
            <option value="">Semua</option>
            <option value="paid"      {{ request('status') === 'paid'      ? 'selected' : '' }}>Lunas</option>
            <option value="unpaid"    {{ request('status') === 'unpaid'    ? 'selected' : '' }}>Belum Lunas</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
    </form>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Invoice</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono font-medium text-blue-700">{{ $invoice->invoice_number }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $invoice->user->name }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $invoice->order->order_number }}</td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $invoice->issued_date->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @if($invoice->status === 'paid')
                            <span class="badge-delivered">Lunas</span>
                        @elseif($invoice->status === 'unpaid')
                            <span class="badge-pending">Belum Lunas</span>
                        @else
                            <span class="badge-cancelled">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Lihat</a>
                            <a href="{{ route('admin.invoices.download', $invoice) }}"
                               class="text-green-600 hover:text-green-800 text-xs font-medium" target="_blank">Download</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada invoice.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection