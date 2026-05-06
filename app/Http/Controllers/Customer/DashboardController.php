<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_orders'      => Order::where('user_id', $user->id)->count(),
            'pending_orders'    => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_spent'       => Order::where('user_id', $user->id)
                                        ->whereIn('status', ['delivered'])
                                        ->sum('total'),
        ];

        $recent_orders = Order::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        $featured_products = Product::with(['brand', 'stock'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('customer.dashboard.index', compact('stats', 'recent_orders', 'featured_products'));
    }
}