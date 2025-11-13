<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 用户必须已登录且角色为 admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can access this page.');
        }

        return $next($request);
    }
}
