<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'    => Product::where('is_active', true)->count(),
            'total_orders'      => Order::count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'total_customers'   => User::where('role', 'customer')->count(),
            'low_stock_count'   => ProductStock::whereColumn('quantity', '<=', 'min_stock')->count(),
            'revenue_today'     => Order::whereDate('created_at', today())
                                        ->whereIn('status', ['confirmed','processing','shipped','delivered'])
                                        ->sum('total'),
            'revenue_month'     => Order::whereMonth('created_at', now()->month)
                                        ->whereIn('status', ['confirmed','processing','shipped','delivered'])
                                        ->sum('total'),
        ];

        $recent_orders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $low_stock_products = ProductStock::with('product.brand')
            ->whereColumn('quantity', '<=', 'min_stock')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recent_orders', 'low_stock_products'));
    }
}