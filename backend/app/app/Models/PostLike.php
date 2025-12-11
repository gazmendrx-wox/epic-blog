<?php

namespace App\Models;

use App\Traits\Model\PostLikeRelationshipTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;
    use PostLikeRelationshipTrait;

    protected $table = 'post_likes';

    protected $fillable = [
        'post_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
