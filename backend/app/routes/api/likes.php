<?php

use App\Http\Controllers\PostLikeController;
use Illuminate\Support\Facades\Route;

// Public routes - Get likes count
Route::get('/posts/{post}/likes/count', [PostLikeController::class, 'count']);

// Authenticated routes - Like/Unlike
Route::middleware('auth:sanctum')->group(function () {
    // Toggle like (like if not liked, unlike if already liked)
    Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle']);
    
    // Check if user has liked a post
    Route::get('/posts/{post}/likes/check', [PostLikeController::class, 'check']);
});

// Admin routes - Get users who liked a post
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/posts/{post}/likes/users', [PostLikeController::class, 'users']);
});
