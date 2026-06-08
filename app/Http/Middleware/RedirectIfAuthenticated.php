<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::guard($guard)->user()->role;
                return match($role) {
                    'super_admin'  => redirect()->route('admin.dashboard'),
                    'admin'        => redirect()->route('admin.dashboard'),
                    'admin_gudang' => redirect()->route('gudang.dashboard'),
                    'owner'        => redirect()->route('owner.dashboard'),
                    default        => redirect()->route('customer.dashboard'),
                };
            }

        return $next($request);
    }
}
}