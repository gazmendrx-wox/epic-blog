<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Http\Resources\NewsletterSubscriberResource;
use App\Services\NewsletterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribe(NewsletterSubscribeRequest $request): JsonResponse
    {
        try {
            $subscriber = $this->newsletterService->subscribe($request->email);

            return response()->json([
                'message' => 'Successfully subscribed to newsletter!',
                'data' => new NewsletterSubscriberResource($subscriber),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to subscribe to newsletter.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unsubscribe from newsletter using token
     */
    public function unsubscribe(string $token): JsonResponse
    {
        try {
            $subscriber = $this->newsletterService->unsubscribe($token);

            if (!$subscriber) {
                return response()->json([
                    'message' => 'Invalid unsubscribe token.',
                ], 404);
            }

            return response()->json([
                'message' => 'Successfully unsubscribed from newsletter.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to unsubscribe from newsletter.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get active subscribers count (admin only)
     */
    public function count(): JsonResponse
    {
        try {
            $count = $this->newsletterService->getActiveSubscribersCount();

            return response()->json([
                'count' => $count,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get subscribers count.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
