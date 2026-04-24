<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function factory_creates_a_valid_post()
    {
        $post = Post::factory()->create();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'user_id' => $post->user_id,
        ]);

        $this->assertInstanceOf(User::class, $post->user);
    }

    /** @test */
    public function definition_returns_correct_attributes()
    {
        $factory = new \Database\Factories\PostFactory(app());

        $attributes = $factory->definition();

        $this->assertIsString($attributes['title']);
        $this->assertIsString($attributes['body']);
        $this->assertArrayHasKey('user_id', $attributes);
    }
}