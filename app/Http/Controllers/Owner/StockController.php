<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;

class StockController extends Controller
{
    public function index()
    {
        $stocks = ProductStock::with(['product.brand', 'product.category'])
            ->whereHas('product')
            ->paginate(15);

        $summary = [
            'total' => ProductStock::whereHas('product')->count(),
            'safe'  => ProductStock::whereHas('product')->whereRaw('quantity > min_stock')->count(),
            'low'   => ProductStock::whereHas('product')->whereRaw('quantity > 0 AND quantity <= min_stock')->count(),
            'empty' => ProductStock::whereHas('product')->where('quantity', 0)->count(),
        ];

        return view('owner.stocks.index', compact('stocks', 'summary'));
    }
}