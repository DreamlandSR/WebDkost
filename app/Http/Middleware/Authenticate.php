<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // API request → jangan redirect
        if ($request->is('api/*') || $request->expectsJson()) {
            return $next($request);  // biarkan auth:sanctum yang handle
        }

        // Web request → redirect jika belum login
        if (!Auth::check()) {
            // Kalau request API → return JSON 401
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
            // Kalau request web → redirect ke login
            return redirect('/login');
        }

        return $next($request);
    }
}