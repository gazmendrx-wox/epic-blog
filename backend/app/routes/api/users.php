<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Admin only routes - User Management
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Get all users
    Route::get('/admin/users', [UserController::class, 'index']);
    
    // Get single user
    Route::get('/admin/users/{id}', [UserController::class, 'show']);
    
    // Update user details
    Route::put('/admin/users/{id}', [UserController::class, 'update']);
    
    // Update user password
    Route::put('/admin/users/{id}/password', [UserController::class, 'updatePassword']);
    
    // Suspend user
    Route::post('/admin/users/{id}/suspend', [UserController::class, 'suspend']);
    
    // Unsuspend user
    Route::post('/admin/users/{id}/unsuspend', [UserController::class, 'unsuspend']);
    
    // Permanently delete user
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
    
    // Get user statistics
    Route::get('/admin/users/{id}/stats', [UserController::class, 'stats']);
    
    // Get platform statistics
    Route::get('/admin/stats', [UserController::class, 'platformStats']);
});
