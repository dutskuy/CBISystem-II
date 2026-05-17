<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Owner DAN admin boleh akses route owner
        if (!in_array(auth()->user()->role, ['owner', 'admin'])) {
            abort(403);
        }

        return $next($request);
    }
}