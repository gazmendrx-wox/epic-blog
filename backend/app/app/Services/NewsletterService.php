<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\DB;

class NewsletterService
{
    /**
     * Subscribe an email to the newsletter
     */
    public function subscribe(string $email): NewsletterSubscriber
    {
        return DB::transaction(function () use ($email) {
            // Check if already subscribed
            $subscriber = NewsletterSubscriber::where('email', $email)->first();

            if ($subscriber) {
                // Reactivate if previously unsubscribed
                if ($subscriber->unsubscribed_at !== null) {
                    $subscriber->unsubscribed_at = null;
                    $subscriber->subscribed_at = now();
                    $subscriber->save();
                }
                return $subscriber;
            }

            // Create new subscription
            return NewsletterSubscriber::create([
                'email' => $email,
                'token' => NewsletterSubscriber::generateToken(),
                'subscribed_at' => now(),
            ]);
        });
    }

    /**
     * Unsubscribe using token
     */
    public function unsubscribe(string $token): ?NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            return null;
        }

        $subscriber->unsubscribed_at = now();
        $subscriber->save();

        return $subscriber;
    }

    /**
     * Get all active subscribers
     */
    public function getActiveSubscribers()
    {
        return NewsletterSubscriber::active()->get();
    }

    /**
     * Get subscriber by email
     */
    public function getByEmail(string $email): ?NewsletterSubscriber
    {
        return NewsletterSubscriber::where('email', $email)->first();
    }

    /**
     * Get subscriber by token
     */
    public function getByToken(string $token): ?NewsletterSubscriber
    {
        return NewsletterSubscriber::where('token', $token)->first();
    }

    /**
     * Get total active subscribers count
     */
    public function getActiveSubscribersCount(): int
    {
        return NewsletterSubscriber::active()->count();
    }
}
