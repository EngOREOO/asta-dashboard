<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to authenticated users
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $timeout = config('session.timeout', 30); // Default 30 minutes
            
            // If last activity is set and user has been inactive for too long
            if ($lastActivity && (time() - $lastActivity) > ($timeout * 60)) {
                Auth::logout();
                Session::flush();
                
                return redirect()->route('login')->with('session_expired', 'انتهت جلستك بسبب عدم النشاط. يرجى تسجيل الدخول مرة أخرى.');
            }
            
            // Update last activity timestamp
            Session::put('last_activity', time());
        }

        return $next($request);
    }
}
