<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Category;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $adminReport = app(\App\Http\Controllers\Admin\ReportController::class);
        
        $year  = $request->get('year', now()->year);
        $month = $request->get('month');

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

        $profitData = OrderItem::select(
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('SUM(order_items.quantity * products.cost_price) as total_cost'),
                DB::raw('SUM(order_items.subtotal - (order_items.quantity * products.cost_price)) as total_profit')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })->first();

        $monthlyRevenue = Order::whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->whereYear('created_at', $year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as total'))
            ->groupBy('month')->orderBy('month')->get();

        $topProducts = OrderItem::select('product_name', 'product_sku',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->groupBy('product_name', 'product_sku')
            ->orderByDesc('total_qty')->take(10)->get();

        $brandRevenue = OrderItem::select(
                DB::raw('products.brand_id'),
                DB::raw('MAX(brands.name) as brand_name'),
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->groupBy('products.brand_id')
            ->orderByDesc('total_revenue')->get();

        $orders = (clone $baseQuery)->with(['user','items'])->latest()->paginate(20)->withQueryString();
        $years  = Order::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');

        return view('owner.reports.sales', compact(
            'summary', 'profitData', 'monthlyRevenue', 'topProducts',
            'brandRevenue', 'orders', 'years', 'year', 'month'
        ));
    }

    public function exportSales(Request $request)
    {
        return app(\App\Http\Controllers\Admin\ReportController::class)->exportSales($request);
    }

    public function stock(Request $request)
    {
        $search     = $request->get('search');
        $filterType = $request->get('type');

        $query = ProductStock::with(['product.brand', 'product.category']) ->whereHas('product');

        if ($search) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%'.$search.'%')
                ->orWhere('sku', 'like', '%'.$search.'%')
            );
        }

        if ($filterType === 'low')   $query->whereRaw('quantity > 0 AND quantity <= min_stock');
        if ($filterType === 'empty') $query->where('quantity', 0);
        if ($filterType === 'safe')  $query->whereRaw('quantity > min_stock');

        if ($request->filled('brand_id')) {
            $query->whereHas('product', fn($q) => $q->where('brand_id', $request->brand_id));
        }
        if ($request->filled('category_id')) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $request->category_id));
        }

        $stocks  = $query->paginate(20)->withQueryString();
        $brands     = Brand::where('is_active', true)->get();     
        $categories = Category::where('is_active', true)->get();  

        $summary = [
            'total'       => ProductStock::count(),
            'total_sku'   => ProductStock::count(),
            'total_qty'   => ProductStock::sum('quantity'),
            'safe'        => ProductStock::whereRaw('quantity > min_stock')->count(),
            'safe_count'  => ProductStock::whereRaw('quantity > min_stock')->count(),
            'low'         => ProductStock::whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'low_count'   => ProductStock::whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'empty'       => ProductStock::where('quantity', 0)->count(),
            'empty_count' => ProductStock::where('quantity', 0)->count(),
        ];

        return view('owner.reports.stock', compact(
            'stocks', 'summary', 'brands', 'categories'
        ));
    }

    public function profit(Request $request)
    {

        $year  = $request->get('year', now()->year);
        $month = $request->get('month');

        $summary = [
            'total_revenue'  => Order::whereIn('status', ['confirmed','processing','shipped','delivered'])->whereYear('created_at', $year)->when($month, fn($q) => $q->whereMonth('created_at', $month))->sum('total'),
            'total_tax'      => Order::whereIn('status', ['confirmed','processing','shipped','delivered'])->whereYear('created_at', $year)->when($month, fn($q) => $q->whereMonth('created_at', $month))->sum('tax'),
            'total_subtotal' => Order::whereIn('status', ['confirmed','processing','shipped','delivered'])->whereYear('created_at', $year)->when($month, fn($q) => $q->whereMonth('created_at', $month))->sum('subtotal'),
            'total_cost'     => OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                                    ->whereHas('order', function($q) use ($year, $month) {
                                        $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                                          ->whereYear('created_at', $year);
                                        if ($month) $q->whereMonth('created_at', $month);
                                    })->sum(DB::raw('order_items.quantity * products.cost_price')),
            'total_profit'   => OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                                    ->whereHas('order', function($q) use ($year, $month) {
                                        $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                                          ->whereYear('created_at', $year);
                                        if ($month) $q->whereMonth('created_at', $month);
                                    })->sum(DB::raw('order_items.subtotal - (order_items.quantity * products.cost_price)')),
        ];

        $summary['margin'] = $summary['total_revenue'] > 0
            ? round(($summary['total_profit'] / $summary['total_revenue']) * 100, 1)
            : 0;

        $monthlyProfit = Order::whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->whereYear('created_at', $year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as revenue'), DB::raw('SUM(subtotal) as subtotal'))
            ->groupBy('month')->orderBy('month')->get();

        $productProfit = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->select('order_items.product_name', 'order_items.product_sku',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('SUM(order_items.quantity * products.cost_price) as total_cost'),
                DB::raw('SUM(order_items.subtotal - (order_items.quantity * products.cost_price)) as total_profit')
            )
            ->groupBy('order_items.product_name', 'order_items.product_sku')
            ->orderByDesc('total_profit')->take(10)->get();

        $years = Order::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');

        return view('owner.reports.profit', compact(
            'summary', 'monthlyProfit', 'productProfit', 'years', 'year', 'month'
        ));
    }
}