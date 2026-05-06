@extends('layouts.admin')
@section('title', 'Detail Invoice')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.invoices.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
            <h2 class="text-xl font-bold text-gray-800 mt-1">{{ $invoice->invoice_number }}</h2>
        </div>
        <a href="{{ route('admin.invoices.download', $invoice) }}" target="_blank" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download Invoice
        </a>
    </div>

    {{-- Invoice Preview --}}
    @include('admin.invoices.pdf', ['invoice' => $invoice, 'preview' => true])
</div>
@endsection