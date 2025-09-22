<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'=>'error',
                    'message'=>'Unauthorized',
                    'data'=>null
                ],403);
            } else {
                return redirect('/')->with('error', 'Unauthorized access.');
            }
        }
        return $next($request);
    }
}
