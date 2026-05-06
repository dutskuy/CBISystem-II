<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100|unique:brands,name',
            'origin_country' => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'boolean',
        ]);

        $validated['slug']      = Str::slug($request->name);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand berhasil ditambahkan.');
    }

    public function show(Brand $brand)
    {
        $brand->loadCount('products');
        $products = $brand->products()->with('category', 'stock')->latest()->paginate(10);
        return view('admin.brands.show', compact('brand', 'products'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100|unique:brands,name,' . $brand->id,
            'origin_country' => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'boolean',
        ]);

        $validated['slug']      = Str::slug($request->name);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($brand->logo) Storage::disk('public')->delete($brand->logo);
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Brand tidak dapat dihapus karena masih memiliki produk.');
        }

        if ($brand->logo) Storage::disk('public')->delete($brand->logo);

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand berhasil dihapus.');
    }
}