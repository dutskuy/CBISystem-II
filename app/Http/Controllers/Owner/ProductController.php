<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'stock']);
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%');
        }
        $products   = $query->latest()->paginate(15)->withQueryString();
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('owner.products.index', compact('products', 'brands', 'categories'));
    }
}