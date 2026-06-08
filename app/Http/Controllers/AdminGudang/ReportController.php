<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $search     = $request->get('search');
        $filterType = $request->get('type');

        $query = ProductStock::with(['product.brand', 'product.category'])->whereHas('product');

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

        $stocks     = $query->paginate(20)->withQueryString();
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        $summary = [
            'total'       => ProductStock::whereHas('product')->count(),
            'total_sku'   => ProductStock::whereHas('product')->count(),
            'total_qty'   => ProductStock::whereHas('product')->sum('quantity'),
            'safe'        => ProductStock::whereHas('product')->whereRaw('quantity > min_stock')->count(),
            'safe_count'  => ProductStock::whereHas('product')->whereRaw('quantity > min_stock')->count(),
            'low'         => ProductStock::whereHas('product')->whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'low_count'   => ProductStock::whereHas('product')->whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'empty'       => ProductStock::whereHas('product')->where('quantity', 0)->count(),
            'empty_count' => ProductStock::whereHas('product')->where('quantity', 0)->count(),
        ];

        return view('gudang.reports.stock', compact('stocks', 'summary', 'brands', 'categories'));
    }
}