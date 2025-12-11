<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    /**
     * Generate a unique unsubscribe token
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if subscriber is active
     */
    public function isActive(): bool
    {
        return $this->subscribed_at !== null && $this->unsubscribed_at === null;
    }

    /**
     * Scope to get only active subscribers
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('subscribed_at')
            ->whereNull('unsubscribed_at');
    }
}
