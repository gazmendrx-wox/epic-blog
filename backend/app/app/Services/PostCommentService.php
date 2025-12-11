<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PostCommentService
{
    /**
     * Create a new comment on a post
     */
    public function createComment(Post $post, User $user, string $content): PostComment
    {
        $comment = new PostComment();
        $comment->post_id = $post->id;
        $comment->user_id = $user->id;
        $comment->content = $content;
        $comment->save();

        return $comment->fresh(['user', 'post']);
    }

    /**
     * Update a comment
     */
    public function updateComment(PostComment $comment, string $content): PostComment
    {
        $comment->content = $content;
        $comment->save();

        return $comment->fresh(['user', 'post']);
    }

    /**
     * Delete a comment
     */
    public function deleteComment(PostComment $comment): bool
    {
        return $comment->delete();
    }

    /**
     * Get all comments for a post (paginated)
     */
    public function getPostComments(Post $post, int $perPage = 20)
    {
        return PostComment::where('post_id', $post->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get comments count for a post
     */
    public function getCommentsCount(Post $post): int
    {
        return PostComment::where('post_id', $post->id)->count();
    }

    /**
     * Get all comments by a user
     */
    public function getUserComments(User $user, int $perPage = 20)
    {
        return PostComment::where('user_id', $user->id)
            ->with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Check if user can delete comment
     */
    public function canDeleteComment(PostComment $comment, User $user): bool
    {
        // User can delete own comment or admin can delete any comment
        return $comment->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Check if user can update comment
     */
    public function canUpdateComment(PostComment $comment, User $user): bool
    {
        // Only the comment owner can update their comment
        return $comment->user_id === $user->id;
    }
}
