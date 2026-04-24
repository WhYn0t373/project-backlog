<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles Comma‑separated list of allowed roles.
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();

        if (! $user) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied: not authenticated.');
        }

        $requiredRoles = array_map('trim', explode(',', $roles));

        if ($user->role && in_array($user->role->name, $requiredRoles, true)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, 'Access denied: insufficient permissions.');
    }
}