<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Include modular route files
require __DIR__.'/api/auth.php';
require __DIR__.'/api/posts.php';
require __DIR__.'/api/users.php';
require __DIR__.'/api/likes.php';
require __DIR__.'/api/comments.php';
require __DIR__.'/api/newsletter.php';
