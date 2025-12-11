<?php

namespace App\Traits\Model;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PostLikeRelationshipTrait
{
    /**
     * Get the post that was liked
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who liked the post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
