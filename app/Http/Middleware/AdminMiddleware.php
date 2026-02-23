<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // If not logged in or not an admin, redirect to shop with a warning
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('shop.index')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}