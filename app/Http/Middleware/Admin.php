<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin) { // Adjust this based on your user model
            return $next($request);
        }

        // Redirect or abort if not an admin
        return redirect('/')->with('error', 'You do not have admin access.');
        // Or: abort(403, 'Unauthorized action.');
    }
}