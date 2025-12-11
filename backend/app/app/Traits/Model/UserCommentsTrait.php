<?php

namespace App\Traits\Model;

use App\Models\PostComment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserCommentsTrait
{
    /**
     * Get all comments created by this user
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'user_id');
    }

    /**
     * Get user's comments count
     */
    public function commentsCount(): int
    {
        return $this->comments()->count();
    }
}
