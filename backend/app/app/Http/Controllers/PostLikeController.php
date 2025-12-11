<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostLikeService;
use Illuminate\Http\JsonResponse;

class PostLikeController extends Controller
{
    public function __construct(
        private PostLikeService $postLikeService
    ) {}

    /**
     * Toggle like on a post (like/unlike)
     */
    public function toggle(Post $post): JsonResponse
    {
        $user = auth()->user();

        // Only allow approved posts to be liked
        if ($post->status !== 'approved') {
            return response()->json([
                'message' => 'You can only like approved posts'
            ], 403);
        }

        $result = $this->postLikeService->toggleLike($post, $user);

        return response()->json([
            'message' => $result['action'] === 'liked' 
                ? 'Post liked successfully' 
                : 'Post unliked successfully',
            'data' => $result,
        ], 200);
    }

    /**
     * Get likes count for a post
     */
    public function count(Post $post): JsonResponse
    {
        $count = $this->postLikeService->getLikesCount($post);
        $isLiked = auth()->check() 
            ? $this->postLikeService->hasUserLiked($post, auth()->user())
            : false;

        return response()->json([
            'data' => [
                'likes_count' => $count,
                'is_liked' => $isLiked,
            ]
        ], 200);
    }

    /**
     * Check if authenticated user has liked a post
     */
    public function check(Post $post): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'data' => ['is_liked' => false]
            ], 200);
        }

        $isLiked = $this->postLikeService->hasUserLiked($post, auth()->user());

        return response()->json([
            'data' => ['is_liked' => $isLiked]
        ], 200);
    }

    /**
     * Get users who liked a post (admin only)
     */
    public function users(Post $post): JsonResponse
    {
        $users = $this->postLikeService->getUsersWhoLiked($post);

        return response()->json([
            'data' => $users
        ], 200);
    }
}
