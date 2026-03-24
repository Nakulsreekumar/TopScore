<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Check if the user is even logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // 2. Check if their role matches the required role for the route
        if (Auth::user()->role !== $role) {
            // If they try to go somewhere they shouldn't, kick them back to their own dashboard
            $userRole = Auth::user()->role;
            return redirect()->route("$userRole.dashboard")->with('error', 'Access Denied: You do not have permission to view that page.');
        }

        // 3. If they pass both checks, let them through!
        return $next($request);
    }
}