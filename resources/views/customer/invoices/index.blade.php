@extends('layouts.customer')
@section('title', 'Invoice Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Invoice Saya</h1>

    @if($invoices->isEmpty())
        <div class="card text-center py-16">
            <p class="text-gray-400">Belum ada invoice.</p>
        </div>
    @else
        <div class="card p-0 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Invoice</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-medium text-blue-700">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $invoice->order->order_number }}</td>
                            <td class="px-6 py-4 font-semibold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $invoice->issued_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('customer.invoices.show', $invoice) }}"
                                       class="text-blue-600 text-xs hover:underline">Lihat</a>
                                    <a href="{{ route('customer.invoices.download', $invoice) }}"
                                       class="text-green-600 text-xs hover:underline" target="_blank">Download</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($invoices->hasPages())
                <div class="px-6 py-4 border-t">{{ $invoices->links() }}</div>
            @endif
        </div>
    @endif
</div>
@endsection