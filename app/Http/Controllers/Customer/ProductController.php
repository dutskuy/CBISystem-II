<?php

namespace App\Http\Controllers\Customer;

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

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sortOptions = [
            'latest'     => ['created_at', 'desc'],
            'price_asc'  => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'name_asc'   => ['name', 'asc'],
        ];

        $sort = $sortOptions[$request->get('sort', 'latest')] ?? $sortOptions['latest'];
        $query->orderBy($sort[0], $sort[1]);

        $products   = $query->paginate(16)->withQueryString();
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('customer.products.index', compact('products', 'brands', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);

        $product->load(['brand', 'category', 'stock']);

        $related = Product::with(['brand', 'stock'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                $q->where('brand_id', $product->brand_id)
                  ->orWhere('category_id', $product->category_id);
            })
            ->take(4)
            ->get();

        return view('customer.products.show', compact('product', 'related'));
    }
}