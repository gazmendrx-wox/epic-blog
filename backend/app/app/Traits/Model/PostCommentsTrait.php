<?php

namespace App\Traits\Model;

use App\Models\PostComment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PostCommentsTrait
{
    /**
     * Get all comments for this post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id');
    }

    /**
     * Get comments count
     */
    public function commentsCount(): int
    {
        return $this->comments()->count();
    }

    /**
     * Get latest comments
     */
    public function latestComments(int $limit = 10)
    {
        return $this->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
