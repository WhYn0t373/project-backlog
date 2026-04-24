<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function getPolicy(): PostPolicy
    {
        return new PostPolicy();
    }

    /** @test */
    public function any_authenticated_user_can_view_any_posts()
    {
        $user = User::factory()->create();
        $policy = $this->getPolicy();

        $this->assertTrue($policy->viewAny($user));
    }

    /** @test */
    public function any_user_can_view_any_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $policy = $this->getPolicy();

        $this->assertTrue($policy->view($user, $post));
    }

    /** @test */
    public function only_admin_can_create_post()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $policy = $this->getPolicy();

        $this->assertTrue($policy->create($admin));
        $this->assertFalse($policy->create($user));
    }

    /** @test */
    public function only_admin_can_update_post()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $post = Post::factory()->create();
        $policy = $this->getPolicy();

        $this->assertTrue($policy->update($admin, $post));
        $this->assertFalse($policy->update($user, $post));
    }

    /** @test */
    public function only_admin_can_delete_post()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $post = Post::factory()->create();
        $policy = $this->getPolicy();

        $this->assertTrue($policy->delete($admin, $post));
        $this->assertFalse($policy->delete($user, $post));
    }
}