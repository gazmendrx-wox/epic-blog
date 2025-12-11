<?php

namespace App\Traits\Model;

use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PostLikesTrait
{
    /**
     * Get all likes for this post
     */
    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get the count of likes for this post
     */
    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    /**
     * Check if a user has liked this post
     */
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Check if the authenticated user has liked this post
     */
    public function isLikedByAuth(): bool
    {
        return $this->isLikedBy(auth()->user());
    }

    /**
     * Get the like record for a specific user
     */
    public function getLikeByUser(User $user): ?PostLike
    {
        return $this->likes()
            ->where('user_id', $user->id)
            ->first();
    }
}
