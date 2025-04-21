<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
{
    if (!$request->expectsJson()) {
        // Check if the request path starts with 'dos'
        if (str_starts_with($request->path(), 'dos')) {
            return route('dos.login');
        }
        
        // Default login route for other paths
        return route('login'); // You might need to handle this if login doesn't exist
    }

    return null;
}
}
