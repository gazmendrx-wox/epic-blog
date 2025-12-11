<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;

class PostLikeService
{
    /**
     * Toggle like on a post (like if not liked, unlike if already liked)
     */
    public function toggleLike(Post $post, User $user): array
    {
        $like = $post->getLikeByUser($user);

        if ($like) {
            // Unlike: Remove the like
            $like->delete();

            return [
                'action' => 'unliked',
                'liked' => false,
                'likes_count' => $post->likesCount(),
            ];
        }

        // Like: Create new like
        PostLike::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        return [
            'action' => 'liked',
            'liked' => true,
            'likes_count' => $post->likesCount(),
        ];
    }

    /**
     * Like a post (only if not already liked)
     */
    public function likePost(Post $post, User $user): bool
    {
        // Check if already liked
        if ($post->isLikedBy($user)) {
            return false;
        }

        PostLike::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        return true;
    }

    /**
     * Unlike a post (only if already liked)
     */
    public function unlikePost(Post $post, User $user): bool
    {
        $like = $post->getLikeByUser($user);

        if (!$like) {
            return false;
        }

        return $like->delete();
    }

    /**
     * Get likes count for a post
     */
    public function getLikesCount(Post $post): int
    {
        return $post->likesCount();
    }

    /**
     * Check if user has liked a post
     */
    public function hasUserLiked(Post $post, User $user): bool
    {
        return $post->isLikedBy($user);
    }

    /**
     * Get all users who liked a post
     */
    public function getUsersWhoLiked(Post $post)
    {
        return $post->likes()->with('user')->get()->pluck('user');
    }

    /**
     * Get all posts liked by a user
     */
    public function getPostsLikedByUser(User $user)
    {
        return PostLike::where('user_id', $user->id)
            ->with('post')
            ->get()
            ->pluck('post');
    }
}
