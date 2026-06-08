<?php

namespace App\Http\Controllers\AdminGudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_produk'  => Product::where('is_active', true)->count(),
            'stok_aman'     => ProductStock::whereHas('product')->whereRaw('quantity > min_stock')->count(),
            'stok_menipis'  => ProductStock::whereHas('product')->whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'stok_habis'    => ProductStock::whereHas('product')->where('quantity', 0)->count(),
        ];

        // Produk stok menipis
        $lowStockProducts = ProductStock::with(['product.brand'])
            ->whereHas('product')
            ->whereRaw('quantity <= min_stock')
            ->orderBy('quantity')
            ->take(8)
            ->get();

        // Riwayat pergerakan stok terbaru
        $recentMovements = StockMovement::with(['product', 'createdBy'])
            ->latest()
            ->take(10)
            ->get();

        return view('gudang.dashboard', compact('stats', 'lowStockProducts', 'recentMovements'));
    }
}