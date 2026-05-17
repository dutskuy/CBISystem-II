<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders')->withSum('orders', 'total');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('company_name', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('owner.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'customer', 404);
        $orders = Order::where('user_id', $user->id)->with(['items','payment'])->latest()->paginate(10);
        $stats  = [
            'total_orders'  => Order::where('user_id', $user->id)->count(),
            'total_spent'   => Order::where('user_id', $user->id)->whereIn('status', ['confirmed','processing','shipped','delivered'])->sum('total'),
            'completed'     => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
        ];
        return view('owner.customers.show', compact('user', 'orders', 'stats'));
    }
}