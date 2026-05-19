<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['order', 'user'])->latest()->paginate(15);
        return view('owner.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['order.items.product.brand', 'user']);
        return view('owner.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $invoice->load(['order.items.product.brand', 'user']);
        return view('admin.invoices.pdf', compact('invoice'));
    }
}