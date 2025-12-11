<?php

namespace App\Models;

use App\Traits\Model\PostCommentRelationshipTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;
    use PostCommentRelationshipTrait;

    protected $table = 'post_comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
