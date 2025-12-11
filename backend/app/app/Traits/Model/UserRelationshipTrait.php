<?php

namespace App\Traits\Model;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelationshipTrait
{
    /**
     * Get all posts created by the user
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all posts approved by the user (admin)
     */
    public function approvedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'approved_by');
    }
}
