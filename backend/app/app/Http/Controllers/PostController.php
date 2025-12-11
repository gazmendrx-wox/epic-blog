<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
    }

    /**
     * Get all approved posts (public)
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = $this->postService->getAllApprovedPosts();
        return PostResource::collection($posts);
    }

    /**
     * Get a single post by slug (public if approved)
     */
    public function show(string $slug): PostResource|JsonResponse
    {
        $post = $this->postService->getPostBySlug($slug);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Only show approved posts to public, or own posts to author
        if ($post->status !== 'approved' && (!auth()->check() || auth()->id() !== $post->user_id)) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostResource($post);
    }

    /**
     * Create a new post (authenticated)
     */
    public function store(StorePostRequest $request): PostResource
    {
        $post = $this->postService->createPost($request->validated(), $request->user());
        return new PostResource($post);
    }

    /**
     * Update a post (author or admin)
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource|JsonResponse
    {
        // Check authorization
        if (!$request->user()->isAdmin() && $post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post = $this->postService->updatePost($post, $request->validated(), $request->user());
        return new PostResource($post);
    }

    /**
     * Delete a post (author or admin)
     */
    public function destroy(Post $post): JsonResponse
    {
        // Check authorization
        if (!auth()->user()->isAdmin() && $post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->postService->deletePost($post);
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    /**
     * Approve a post (admin only)
     */
    public function approve(Post $post): PostResource|JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post = $this->postService->approvePost($post, auth()->user());
        return new PostResource($post);
    }

    /**
     * Reject a post (admin only)
     */
    public function reject(Post $post): PostResource|JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post = $this->postService->rejectPost($post, auth()->user());
        return new PostResource($post);
    }

    /**
     * Get user's own posts
     */
    public function myPosts(): AnonymousResourceCollection
    {
        $posts = $this->postService->getUserPosts(auth()->user());
        return PostResource::collection($posts);
    }
}
