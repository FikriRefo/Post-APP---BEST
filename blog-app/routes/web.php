<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/{id}/show', [DashboardController::class, 'show'])->name('dashboard.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // profile route
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // dashboard route
    // posts route
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}/detail', [PostController::class, 'detail'])->name('posts.detail');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}/update', [PostController::class, 'update'])->name('posts.update');
    Route::put('/posts/{id}/publish', [PostController::class, 'publish'])->name('posts.publish');
    Route::delete('/posts/{id}/delete', [PostController::class, 'destroy'])->name('posts.destroy');
    // comment route
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    // like route
    Route::post('/post/{post}/like', [PostController::class, 'like'])->name('post.like');

});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin route
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/data', [AdminController::class, 'getDashboardData'])->name('admin.dashboard.data');

    // Category route
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('store.categories');
    Route::get('/admin/categories/{category}', [CategoryController::class, 'edit'])->name('edit.categories');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('update.categories');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('destroy.categories');
});
