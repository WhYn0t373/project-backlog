<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Policies\PostPolicy;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthServiceProviderTest extends TestCase
{
    /** @test */
    public function it_registers_post_policy()
    {
        $provider = new AuthServiceProvider(app());
        $provider->boot();

        $policy = Gate::getPolicyFor(Post::class);

        $this->assertInstanceOf(PostPolicy::class, $policy);
    }
}