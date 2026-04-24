<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * PostController
 *
 * Handles CRUD operations for posts with authorization and validation.
 */
class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Post::class);
            $posts = Post::with('user')->get();
            return response()->json($posts, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error fetching posts: '.$e->getMessage());
            return response()->json(['error' => 'Unable to retrieve posts.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        try {
            $post = Post::create([
                'title'   => $validated['title'],
                'body'    => $validated['body'],
                'user_id' => $request->user()->id,
            ]);

            return response()->json($post, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Error creating post: '.$e->getMessage());
            return response()->json(['error' => 'Unable to create post.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        try {
            $this->authorize('view', $post);
            $post->load('user');
            return response()->json($post, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error fetching post: '.$e->getMessage());
            return response()->json(['error' => 'Unable to retrieve post.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        // Updated validation: both fields required
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        try {
            $post->update($validated);
            $post->load('user');
            return response()->json($post, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error updating post: '.$e->getMessage());
            return response()->json(['error' => 'Unable to update post.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        try {
            $post->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            Log::error('Error deleting post: '.$e->getMessage());
            return response()->json(['error' => 'Unable to delete post.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}