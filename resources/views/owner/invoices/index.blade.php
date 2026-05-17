@extends('layouts.owner')
@section('title', 'Invoice')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Invoice</h1>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Invoice</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-emerald-700 text-xs font-medium">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $invoice->order->order_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->user->name }}</td>
                        <td class="px-6 py-4 font-semibold text-sm">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $invoice->issued_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('owner.invoices.download', $invoice) }}"
                               class="text-emerald-600 text-xs hover:underline" target="_blank">Download</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada invoice.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t">{{ $invoices->links() }}</div>
        @endif
    </div>
</div>
@endsection