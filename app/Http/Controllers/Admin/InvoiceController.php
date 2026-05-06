<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['order', 'user'])->latest();

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%'.$request->search.'%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(15)->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['order.items.product.brand', 'user']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $invoice->load(['order.items.product.brand', 'user']);
        $pdf = view('admin.invoices.pdf', compact('invoice'))->render();

        return response($pdf)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="'.$invoice->invoice_number.'.html"');
    }
}