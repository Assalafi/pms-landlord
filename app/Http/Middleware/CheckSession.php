<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the session contains the user_id, if not redirect to login
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        return $next($request);
    }
}
