<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', null);

        // Revenue per bulan (untuk chart)
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month');

        // Query orders untuk tabel
        $query = Order::with(['user', 'items'])
            ->whereIn('status', ['confirmed','processing','shipped','delivered']);

        $query->whereYear('created_at', $year);
        if ($month) $query->whereMonth('created_at', $month);

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Summary
            $baseQuery = Order::whereIn('status', ['confirmed','processing','shipped','delivered'])
                ->whereYear('created_at', $year);

            if ($month) $baseQuery->whereMonth('created_at', $month);

            $summary = [
                'total_revenue'  => (clone $baseQuery)->sum('total'),
                'total_tax'      => (clone $baseQuery)->sum('tax'),
                'total_subtotal' => (clone $baseQuery)->sum('subtotal'),
                'total_orders'   => (clone $baseQuery)->count(),
                'avg_order'      => (clone $baseQuery)->avg('total'),
                'total_items'    => OrderItem::whereHas('order', function($q) use ($year, $month) {
                    $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                    ->whereYear('created_at', $year);
                    if ($month) $q->whereMonth('created_at', $month);
                })->sum('quantity'),
            ];

            $orders = (clone $baseQuery)->with(['user','items'])->latest()->paginate(20)->withQueryString();

        // Top produk terlaris
        $topProducts = OrderItem::select('product_id', 'product_name', 'product_sku',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->groupBy('product_id', 'product_name', 'product_sku')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Revenue per brand
        $brandRevenue = OrderItem::select(
                DB::raw('products.brand_id'),
                DB::raw('brands.name as brand_name'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_qty'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->groupBy('products.brand_id', 'brands.name')
            ->orderByDesc('total_revenue')
            ->get();

        $years = range(now()->year, 2024);
        $profitData = \App\Models\OrderItem::select(
                    \DB::raw('SUM(order_items.subtotal) as total_revenue'),
                    \DB::raw('SUM(order_items.quantity * products.cost_price) as total_cost'),
                    \DB::raw('SUM(order_items.subtotal - (order_items.quantity * products.cost_price)) as total_profit')
                )
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereHas('order', function($q) use ($year, $month) {
                    $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                    ->whereYear('created_at', $year);
                    if ($month) $q->whereMonth('created_at', $month);
                })
                ->first();

            return view('admin.reports.sales', compact(
                'orders', 'summary', 'monthlyRevenue',
                'topProducts', 'brandRevenue', 'years',
                'year', 'month', 'profitData'   // ← tambah profitData
        ));
    }

    public function exportSales(Request $request)
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', null);

        $orders = Order::with(['user', 'items.product.brand'])
            ->whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->whereYear('created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->latest()
            ->get();

        $filename = 'laporan-penjualan-'.$year.($month ? '-'.str_pad($month,2,'0',STR_PAD_LEFT) : '').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'No. Pesanan', 'Tanggal', 'Pelanggan', 'Email',
                'Total', 'Status', 'Jumlah Item'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y'),
                    $order->user->name,
                    $order->user->email,
                    $order->total,
                    $order->status,
                    $order->items->sum('quantity'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function stock(Request $request)
    {
        $query = ProductStock::with(['product.brand', 'product.category'])
            ->whereHas('product', fn($q) => $q->whereNull('deleted_at'));

        if ($request->filled('brand_id')) {
            $query->whereHas('product', fn($q) => $q->where('brand_id', $request->brand_id));
        }

        if ($request->filled('status')) {
            match($request->status) {
                'low'   => $query->whereColumn('quantity', '<=', 'min_stock')->where('quantity', '>', 0),
                'empty' => $query->where('quantity', 0),
                'safe'  => $query->whereColumn('quantity', '>', 'min_stock'),
                default => null,
            };
        }

        $stocks = $query->get();

        $summary = [
            'total_sku'    => $stocks->count(),
            'total_qty'    => $stocks->sum('quantity'),
            'low_count'    => $stocks->filter(fn($s) => $s->quantity > 0 && $s->quantity <= $s->min_stock)->count(),
            'empty_count'  => $stocks->where('quantity', 0)->count(),
        ];

        $brands = \App\Models\Brand::where('is_active', true)->get();

        return view('admin.reports.stock', compact('stocks', 'summary', 'brands'));
    }
}