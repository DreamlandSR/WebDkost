<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleOrPermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$rolesOrPermissions)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        foreach ($rolesOrPermissions as $item) {
            // Check if it's a role
            if ($user->hasRole($item)) {
                return $next($request);
            }
            
            // Check if it's a permission
            if ($user->hasPermission($item)) {
                return $next($request);
            }
        }
        
        abort(403, 'Unauthorized access.');
    }
}