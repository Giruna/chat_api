<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FriendshipController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Register
Route::post('/register', [RegisterController::class, 'register']);

// Email verification
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify');

// Login
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Send a friend request
    Route::post('/friend-request/send', [FriendshipController::class, 'sendRequest'])
        ->name('friend-request.send');

    // Accept a friend request
    Route::post('/friend-request/accept', [FriendshipController::class, 'acceptRequest']);

    // Reject a friend request
    Route::post('/friend-request/reject', [FriendshipController::class, 'rejectRequest']);

    // Received friend requests
    Route::get('/friend-request/received', [FriendshipController::class, 'receivedFriendRequests']);

    // Friends list
    Route::get('/friends', [UserController::class, 'friendsList']);

    // Users list (paginated)
    Route::get('/users', [UserController::class, 'usersList']);

    // Send a message to a friend
    Route::post('/messages/send', [MessageController::class, 'send']);

    // Get conversation with a friend (paginated)
    Route::get('/messages/{friendId}', [MessageController::class, 'conversation']);
});
