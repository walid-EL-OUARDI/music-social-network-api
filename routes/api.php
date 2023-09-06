<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PostController;
use App\Http\Controllers\Auth\SongController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VideoController;
use App\Http\Controllers\Auth\SongsByUserController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('song', SongController::class);
    Route::get('songs/{id}', [SongsByUserController::class, 'index']);
    Route::resource('video', VideoController::class);
    Route::get('posts/{user_id}', [PostController::class, 'getPosts']);
    Route::resource('post', PostController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
