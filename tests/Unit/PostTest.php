<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $post = new Post();

        $fillable = $post->getFillable();

        $this->assertContains('title', $fillable);
        $this->assertContains('body', $fillable);
        $this->assertContains('user_id', $fillable);
    }
}