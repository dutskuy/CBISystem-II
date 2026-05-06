<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('company_name', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->withCount('orders')
                       ->withSum('orders', 'total')
                       ->latest()
                       ->paginate(15)
                       ->withQueryString();

        $summary = [
            'total'    => User::where('role', 'customer')->count(),
            'active'   => User::where('role', 'customer')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'customer')->where('is_active', false)->count(),
            'new_this_month' => User::where('role', 'customer')
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count(),
        ];

        return view('admin.users.index', compact('users', 'summary'));
    }

    public function show(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $orders = Order::where('user_id', $user->id)
                       ->with(['items', 'payment'])
                       ->latest()
                       ->paginate(10);

        $stats = [
            'total_orders'   => Order::where('user_id', $user->id)->count(),
            'total_spent'    => Order::where('user_id', $user->id)
                                     ->whereIn('status', ['confirmed','processing','shipped','delivered'])
                                     ->sum('total'),
            'pending_orders' => Order::where('user_id', $user->id)
                                     ->where('status', 'pending')
                                     ->count(),
            'completed'      => Order::where('user_id', $user->id)
                                     ->where('status', 'delivered')
                                     ->count(),
        ];

        return view('admin.users.show', compact('user', 'orders', 'stats'));
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 403);

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => \Hash::make($request->new_password),
        ]);

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }
    
    public function toggleActive(User $user)
    {
        abort_if($user->role === 'admin', 403);

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }
}