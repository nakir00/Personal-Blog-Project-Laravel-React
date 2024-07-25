<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('posts', PostController::class);

// AUTHENTIFICATION
// AUTHENTIFICATION

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

// MAKING COMMENTS
// MAKING COMMENTS


Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});


// ROUTES FRIENDS
// ROUTES FRIENDS


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/friends/request/{friendId}', [FriendController::class, 'sendRequest']);
    Route::post('/friends/accept/{userId}', [FriendController::class, 'acceptRequest']);
    Route::get('/friends', [FriendController::class, 'listFriends']);
    Route::get('/friend-requests', [FriendController::class, 'listFriendRequests']);
});

