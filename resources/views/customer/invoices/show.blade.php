@extends('layouts.customer')
@section('title', 'Detail Invoice')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('customer.invoices.index') }}" class="text-sm text-blue-600 hover:underline">← Invoice Saya</a>
            <h1 class="text-xl font-bold text-gray-800 mt-1">{{ $invoice->invoice_number }}</h1>
        </div>
        <a href="{{ route('customer.invoices.download', $invoice) }}" target="_blank"
           class="btn-primary flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download PDF
        </a>
    </div>

    @include('admin.invoices.pdf', ['invoice' => $invoice, 'preview' => true])
</div>
@endsection