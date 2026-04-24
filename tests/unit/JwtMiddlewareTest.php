<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\JwtMiddleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Carbon\Carbon;

class JwtMiddlewareTest extends TestCase
{
    public function test_handles_missing_authorization_header()
    {
        $middleware = new JwtMiddleware();
        $request = Request::create('/test', 'GET');
        $response = $middleware->handle($request, function ($req) {
            return null;
        });

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Missing or invalid Authorization header.']),
            $response->getContent()
        );
    }

    public function test_handles_invalid_token()
    {
        $middleware = new JwtMiddleware();
        $request = Request::create('/test', 'GET');
        $request->headers->set('Authorization', 'Bearer invalid.token.here');

        $response = $middleware->handle($request, function ($req) {
            return null;
        });

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Invalid token.']),
            $response->getContent()
        );
    }

    public function test_valid_token_attaches_user()
    {
        $user = User::factory()->create();

        $ttl = env('JWT_TTL', 60);
        $expiration = Carbon::now()->addMinutes($ttl)->timestamp;
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => Carbon::now()->timestamp,
            'exp' => $expiration,
        ];
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        $middleware = new JwtMiddleware();
        $request = Request::create('/test', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $next = function ($req) {
            return response('next');
        };

        $response = $middleware->handle($request, $next);

        $this->assertEquals('next', $response->getContent());
        $this->assertEquals($user->id, $request->attributes->get('user')->id);
    }
}