<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Register a new user and return a JWT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registration failed.'], 500);
        }

        $token = $this->generateToken($user);

        return response()->json(['token' => $token], 201);
    }

    /**
     * Login a user and return a JWT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $this->generateToken($user);

        return response()->json(['token' => $token], 200);
    }

    /**
     * Logout a user. In a stateless JWT system this typically
     * just informs the client to discard the token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }

    /**
     * Generate a JWT for the given user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    public function generateToken(User $user): string
    {
        $ttl = env('JWT_TTL', 60); // minutes
        $expiration = Carbon::now()->addMinutes($ttl)->timestamp;

        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => Carbon::now()->timestamp,
            'exp' => $expiration,
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
}