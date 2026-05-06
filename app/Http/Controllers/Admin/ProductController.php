<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'stock']);

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

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function create()
    {
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id'      => 'required|exists:brands,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('products', 'sku')->whereNull('deleted_at'),
            ],
            'part_number'   => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'specification' => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'cost_price'    => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'boolean',
            // Stock fields
            'quantity'      => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
            'unit'          => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $validated['slug']      = Str::slug($request->name) . '-' . Str::random(5);
            $validated['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($validated);

            // Buat stock record
            ProductStock::create([
                'product_id' => $product->id,
                'quantity'   => $validated['quantity'],
                'min_stock'  => $validated['min_stock'],
                'unit'       => $validated['unit'],
            ]);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'stock']);
        $movements = $product->stockMovements()
            ->with('createdBy')
            ->latest()
            ->take(10)
            ->get();
        return view('admin.products.show', compact('product', 'movements'));
    }

    public function edit(Product $product)
    {
        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        $product->load('stock');
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'brand_id'      => 'required|exists:brands,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('products', 'sku')->ignore($product->id)->whereNull('deleted_at'),
            ],
            'part_number'   => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'specification' => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'cost_price'   => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'boolean',
            'min_stock'     => 'required|integer|min:0',
            'unit'          => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $request, $product) {
            $validated['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                if ($product->image) Storage::disk('public')->delete($product->image);
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($validated);

            // Update stock settings (min_stock & unit saja, quantity via StockController)
            $product->stock()->updateOrCreate(
                ['product_id' => $product->id],
                ['min_stock' => $validated['min_stock'], 'unit' => $validated['unit']]
            );
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah pernah dipesan.');
        }

        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}