<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // If the user is already authenticated, redirect them to the dashboard
        if ($request->user()) {
            // For Inertia navigation, force a hard redirect so URL updates immediately
            if ($request->header('X-Inertia')) {
                return Inertia::location('/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return $next($request);
    }
}


