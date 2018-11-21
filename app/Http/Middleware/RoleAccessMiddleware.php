<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param                           $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guest() || !$request->user()->hasRole($role)) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
