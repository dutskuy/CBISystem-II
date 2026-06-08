<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminGudangMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // super_admin, admin, dan admin_gudang boleh akses
        if (!in_array(auth()->user()->role, ['super_admin', 'admin', 'admin_gudang'])) {
            abort(403);
        }

        return $next($request);
    }
}