<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'stock'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%')
                  ->orWhere('part_number', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('gudang.products.index', compact('products', 'brands', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'stock']);

        $movements = \App\Models\StockMovement::where('product_id', $product->id)
            ->with('createdBy')
            ->latest()
            ->take(20)
            ->get();

        return view('gudang.products.show', compact('product', 'movements'));
    }
}