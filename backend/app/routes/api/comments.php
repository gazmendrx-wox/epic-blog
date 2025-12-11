<?php

use App\Http\Controllers\PostCommentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('posts/{post}/comments', [PostCommentController::class, 'index']);
Route::get('posts/{post}/comments/count', [PostCommentController::class, 'count']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts/{post}/comments', [PostCommentController::class, 'store']);
    Route::put('comments/{comment}', [PostCommentController::class, 'update']);
    Route::delete('comments/{comment}', [PostCommentController::class, 'destroy']);
});
