<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);

        $orders = $query->paginate(15)->withQueryString();

        $summary = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('owner.orders.index', compact('orders', 'summary'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product.brand', 'payment', 'invoice', 'user']);
        return view('owner.orders.show', compact('order'));
    }
}