<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        $products   = Product::with(['brand', 'stock'])
                        ->where('is_active', true)
                        ->latest()
                        ->take(8)
                        ->get();

        return view('landing', compact('brands', 'categories', 'products'));
    }
}