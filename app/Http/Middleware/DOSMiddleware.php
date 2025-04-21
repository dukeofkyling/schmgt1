<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DOSMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'dos') {
            return $next($request);
        }
        
        return redirect()->route('dos.login')->with('error', 'You do not have access to the DOS panel.');
    }
}