<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])->latest();

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%'.$request->search.'%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        $summary = [
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'summary'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.brand', 'payment', 'invoice']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            $order->update(['status' => $newStatus]);

            // Kurangi stok saat order dikonfirmasi
            if ($newStatus === 'confirmed' && $oldStatus === 'pending') {
                foreach ($order->items as $item) {
                    $stock = $item->product->stock;
                    if ($stock) {
                        $stockBefore = $stock->quantity;
                        $stockAfter  = max(0, $stockBefore - $item->quantity);
                        $stock->update(['quantity' => $stockAfter]);

                        StockMovement::create([
                            'product_id'   => $item->product_id,
                            'type'         => 'out',
                            'quantity'     => $item->quantity,
                            'stock_before' => $stockBefore,
                            'stock_after'  => $stockAfter,
                            'reference'    => $order->order_number,
                            'notes'        => 'Penjualan - Order #'.$order->order_number,
                            'created_by'   => auth()->id(),
                        ]);
                    }
                }
            }

            // Kembalikan stok jika order dibatalkan setelah confirmed
            if ($newStatus === 'cancelled' && in_array($oldStatus, ['confirmed','processing','shipped'])) {
                foreach ($order->items as $item) {
                    $stock = $item->product->stock;
                    if ($stock) {
                        $stockBefore = $stock->quantity;
                        $stockAfter  = $stockBefore + $item->quantity;
                        $stock->update(['quantity' => $stockAfter]);

                        StockMovement::create([
                            'product_id'   => $item->product_id,
                            'type'         => 'in',
                            'quantity'     => $item->quantity,
                            'stock_before' => $stockBefore,
                            'stock_after'  => $stockAfter,
                            'reference'    => $order->order_number,
                            'notes'        => 'Pembatalan Order #'.$order->order_number,
                            'created_by'   => auth()->id(),
                        ]);
                    }
                }
            }

            // Buat invoice otomatis saat delivered
            if ($newStatus === 'delivered' && !$order->invoice) {
                Invoice::create([
                    'invoice_number' => 'INV-'.date('Y').'-'.str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    'order_id'       => $order->id,
                    'user_id'        => $order->user_id,
                    'subtotal'       => $order->subtotal,
                    'tax'            => $order->tax,
                    'total'          => $order->total,
                    'issued_date'    => now(),
                    'due_date'       => now(),
                    'status'         => 'paid',
                ]);
            }
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui ke "'.ucfirst($newStatus).'".');
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['order.user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.orders.payments', compact('payments'));
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Update status order
        $payment->order->update(['status' => 'confirmed']);

        // Kurangi stok
        foreach ($payment->order->items as $item) {
            $stock = $item->product->stock;
            if ($stock) {
                $stockBefore = $stock->quantity;
                $stockAfter  = max(0, $stockBefore - $item->quantity);
                $stock->update(['quantity' => $stockAfter]);

                StockMovement::create([
                    'product_id'   => $item->product_id,
                    'type'         => 'out',
                    'quantity'     => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after'  => $stockAfter,
                    'reference'    => $payment->order->order_number,
                    'notes'        => 'Penjualan - '.$payment->order->order_number,
                    'created_by'   => auth()->id(),
                ]);
            }
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Pesanan dikonfirmasi.');
    }

    public function rejectPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Pembayaran ditolak. Pelanggan perlu upload ulang bukti transfer.');
    }
}