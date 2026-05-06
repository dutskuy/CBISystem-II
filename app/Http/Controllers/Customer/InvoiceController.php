<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('user_id', auth()->id())
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('customer.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        $invoice->load(['order.items.product.brand', 'user']);
        return view('customer.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        $invoice->load(['order.items.product.brand', 'user']);
        return view('admin.invoices.pdf', compact('invoice'));
    }
}