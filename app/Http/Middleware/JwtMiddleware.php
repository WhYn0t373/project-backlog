<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['message' => 'Missing or invalid Authorization header.'], 401);
        }

        $token = $matches[1];

        try {
            $payload = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userId = $payload->sub;

            $user = User::find($userId);

            if (!$user) {
                throw new Exception('User not found');
            }

            // Attach user to request
            $request->attributes->set('user', $user);
            Auth::setUser($user);

        } catch (Exception $e) {
            Log::warning('JWT validation failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        return $next($request);
    }
}