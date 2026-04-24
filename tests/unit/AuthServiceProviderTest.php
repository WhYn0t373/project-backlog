<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Providers\AuthServiceProvider;

class AuthServiceProviderTest extends TestCase
{
    public function test_jwt_middleware_alias_is_registered()
    {
        $provider = new AuthServiceProvider(app());
        $provider->boot();

        $this->assertTrue(Route::hasMiddleware('jwt'));
        $this->assertEquals(
            \App\Http\Middleware\JwtMiddleware::class,
            Route::getMiddleware('jwt')
        );
    }
}