<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductStock::with(['product.brand', 'product.category'])
            ->whereHas('product');

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%')
            );
        }

        if ($request->filled('type')) {
            if ($request->type === 'low')   $query->whereRaw('quantity > 0 AND quantity <= min_stock');
            if ($request->type === 'empty') $query->where('quantity', 0);
            if ($request->type === 'safe')  $query->whereRaw('quantity > min_stock');
        }

        $stocks  = $query->paginate(15)->withQueryString();
        $summary = [
            'total' => ProductStock::whereHas('product')->count(),
            'safe'  => ProductStock::whereHas('product')->whereRaw('quantity > min_stock')->count(),
            'low'   => ProductStock::whereHas('product')->whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'empty' => ProductStock::whereHas('product')->where('quantity', 0)->count(),
        ];

        return view('gudang.stocks.index', compact('stocks', 'summary'));
    }

    public function adjust(Product $product)
    {
        $product->load(['stock', 'brand']);
        return view('gudang.stocks.adjust', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'type'     => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:255',
        ]);

        $stock = $product->stock;
        $stockBefore = $stock->quantity;

        if ($request->type === 'in') {
            $stockAfter = $stockBefore + $request->quantity;
        } elseif ($request->type === 'out') {
            if ($request->quantity > $stockBefore) {
                return back()->withErrors(['quantity' => 'Jumlah pengeluaran melebihi stok tersedia ('.$stockBefore.' '.$stock->unit.')']);
            }
            $stockAfter = $stockBefore - $request->quantity;
        } else {
            $stockAfter = $request->quantity;
        }

        $stock->update(['quantity' => $stockAfter]);

        StockMovement::create([
            'product_id'   => $product->id,
            'type'         => $request->type,
            'quantity'     => $request->quantity,
            'stock_before' => $stockBefore,
            'stock_after'  => $stockAfter,
            'reference'    => $request->reference ?? null,
            'notes'        => $request->notes,
            'created_by'   => auth()->id(),
        ]);

        return redirect()->route('gudang.stocks.index')
            ->with('success', 'Stok "'.$product->name.'" berhasil diperbarui.');
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'createdBy'])->latest();

        if ($request->filled('search')) {
            $query->whereHas('product', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%')
            );
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->paginate(20)->withQueryString();
        return view('gudang.stocks.movements', compact('movements'));
    }

    public function lowStock()
    {
        $products = ProductStock::with(['product.brand', 'product.category'])
            ->whereHas('product')
            ->whereRaw('quantity <= min_stock')
            ->orderBy('quantity')
            ->paginate(15);

        return view('gudang.stocks.low-stock', compact('products'));
    }
}