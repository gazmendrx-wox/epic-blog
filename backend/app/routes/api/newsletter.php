<?php

use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe']);

// Admin route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/newsletter/count', [NewsletterController::class, 'count']);
});
