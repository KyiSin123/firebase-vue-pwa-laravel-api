<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SimpleController;
use App\Http\Controllers\Api\NotificationController;

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


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/categories', [PostController::class, 'getCategories'])->name('categories');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/post/list', [PostController::class, 'index'])->name('posts.index');
    Route::post('/post/create', [PostController::class, 'store'])->name('posts.create');
    Route::get('/post/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('/post/edit/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/post/delete/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/token/store', [NotificationController::class, 'store'])->name('token.store');
    Route::post('/simple', [SimpleController::class, 'store'])->name('simple.store');
    Route::get('/simple', [SimpleController::class, 'index'])->name('simple.index');
    Route::get('/simple/{id}', [SimpleController::class, 'edit'])->name('simple.edit');
    Route::patch('/simple', [SimpleController::class, 'update'])->name('simple.update');
    Route::delete('/simple/{id}', [SimpleController::class, 'destroy'])->name('simple.destroy');
});
