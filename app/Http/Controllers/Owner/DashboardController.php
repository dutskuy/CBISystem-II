<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue'   => Order::whereIn('status', ['confirmed','processing','shipped','delivered'])->sum('total'),
            'total_profit'    => OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                                    ->whereHas('order', fn($q) => $q->whereIn('status', ['confirmed','processing','shipped','delivered']))
                                    ->sum(DB::raw('order_items.subtotal - (order_items.quantity * products.cost_price)')),
            'total_orders'    => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'low_stock'       => \App\Models\ProductStock::whereRaw('quantity <= min_stock')->count(),
        ];

        // Revenue 6 bulan terakhir
        $monthlyRevenue = Order::whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total'),
                DB::raw('SUM(tax) as tax'),
                DB::raw('SUM(subtotal) as subtotal')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Top 5 produk
        $topProducts = OrderItem::select('product_name', 'product_sku',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', fn($q) => $q->whereIn('status', ['confirmed','processing','shipped','delivered']))
            ->groupBy('product_name', 'product_sku')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Pesanan terbaru
        $recentOrders = Order::with(['user', 'payment'])
            ->latest()->take(8)->get();

        return view('owner.dashboard', compact(
            'stats', 'monthlyRevenue', 'topProducts', 'recentOrders'
        ));
    }
}