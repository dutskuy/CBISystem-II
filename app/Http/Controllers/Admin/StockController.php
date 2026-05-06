<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductStock::with(['product.brand', 'product.category'])
            ->whereHas('product', fn($q) => $q->whereNull('deleted_at'));

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('brand_id')) {
            $query->whereHas('product', fn($q) => $q->where('brand_id', $request->brand_id));
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('quantity', '<=', 'min_stock');
            } elseif ($request->stock_status === 'empty') {
                $query->where('quantity', 0);
            } elseif ($request->stock_status === 'safe') {
                $query->whereColumn('quantity', '>', 'min_stock');
            }
        }

        $stocks = $query->latest()->paginate(15)->withQueryString();

        $brands = \App\Models\Brand::where('is_active', true)->get();

        $summary = [
            'total'     => ProductStock::count(),
            'low'       => ProductStock::whereColumn('quantity', '<=', 'min_stock')->where('quantity', '>', 0)->count(),
            'empty'     => ProductStock::where('quantity', 0)->count(),
            'safe'      => ProductStock::whereColumn('quantity', '>', 'min_stock')->count(),
        ];

        return view('admin.stocks.index', compact('stocks', 'brands', 'summary'));
    }

    public function adjust(Product $product)
    {
        $product->load(['stock', 'brand', 'category']);
        return view('admin.stocks.adjust', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type'      => 'required|in:in,out,adjustment',
            'quantity'  => 'required|integer|min:1',
            'notes'     => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($validated, $product) {
            $stock = $product->stock;
            $stockBefore = $stock->quantity;

            if ($validated['type'] === 'in') {
                $stockAfter = $stockBefore + $validated['quantity'];
            } elseif ($validated['type'] === 'out') {
                if ($validated['quantity'] > $stockBefore) {
                    throw new \Exception('Jumlah pengurangan melebihi stok yang tersedia.');
                }
                $stockAfter = $stockBefore - $validated['quantity'];
            } else {
                // adjustment — set langsung ke nilai baru
                $stockAfter = $validated['quantity'];
            }

            // Update stok
            $stock->update(['quantity' => $stockAfter]);

            // Catat pergerakan
            StockMovement::create([
                'product_id'   => $product->id,
                'type'         => $validated['type'],
                'quantity'     => $validated['type'] === 'adjustment'
                                    ? abs($stockAfter - $stockBefore)
                                    : $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'reference'    => $validated['reference'] ?? null,
                'notes'        => $validated['notes'] ?? null,
                'created_by'   => auth()->id(),
            ]);
        });

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stok produk "'.$product->name.'" berhasil diperbarui.');
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['product.brand', 'createdBy']);

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(20)->withQueryString();

        return view('admin.stocks.movements', compact('movements'));
    }

    public function lowStock()
    {
        $stocks = ProductStock::with(['product.brand', 'product.category'])
            ->whereColumn('quantity', '<=', 'min_stock')
            ->whereHas('product', fn($q) => $q->whereNull('deleted_at')->where('is_active', true))
            ->orderBy('quantity', 'asc')
            ->paginate(20);

        return view('admin.stocks.low-stock', compact('stocks'));
    }
}