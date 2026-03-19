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
            return redirect('/login');
        }

        return $next($request);
    }
}