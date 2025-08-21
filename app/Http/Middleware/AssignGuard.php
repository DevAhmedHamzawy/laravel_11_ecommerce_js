<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null, $redirectTo = '/login'): Response
    {
        if (!Auth::guard($guard)->check()) {
            return redirect($redirectTo);
        }
        Auth::shouldUse($guard);
        return $next($request);
    }
}
