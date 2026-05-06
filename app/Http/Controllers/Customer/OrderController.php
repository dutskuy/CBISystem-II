<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id())
            ->with(['items', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load(['items.product.brand', 'payment', 'invoice']);
        return view('customer.orders.show', compact('order'));
    }

    public function uploadPayment(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if(!$order->payment, 404);
        abort_if($order->payment->status === 'verified', 422);

        $request->validate([
            'transfer_proof'  => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'sender_name'     => 'required|string|max:100',
            'sender_bank'     => 'required|string|max:50',
            'transfer_date'   => 'required|date|before_or_equal:today',
        ]);

        // Hapus bukti lama jika ada
        if ($order->payment->transfer_proof) {
            Storage::disk('public')->delete($order->payment->transfer_proof);
        }

        $path = $request->file('transfer_proof')->store('payments', 'public');

        $order->payment->update([
            'transfer_proof' => $path,
            'sender_name'    => $request->sender_name,
            'sender_bank'    => $request->sender_bank,
            'transfer_date'  => $request->transfer_date,
            'status'         => 'uploaded',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function cancel(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if(!in_array($order->status, ['pending']), 422);

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}