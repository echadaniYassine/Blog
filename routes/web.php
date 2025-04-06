<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ShareController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home Page: Display latest posts
Route::get('/', [PostController::class, 'home'])->name('home');

// View all posts (with optional ?type=news/book/normal)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// View single post
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// View all members/bloggers
Route::get('/members', function () {
    $members = User::where('role', 'blogger')->get();
    return view('pages.members', compact('members'));
})->name('members');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class, 'register']);

    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // User profile
    Route::get('/profile/{id}', [UserController::class, 'show'])->name('user.profile');
    Route::put('/profile/{id}', [UserController::class, 'update']);

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Post management
    Route::get('/create', [PostController::class, 'createPostBlog'])->name('posts.createPostBlog');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');


    // Likes
    Route::post('/posts/{post}/like-toggle', [LikeController::class, 'toggleLike'])->name('likes.toggle');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comment.like');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggle'])->name('comments.likes.toggle');


    // Share
    Route::post('/posts/{post}/share', [ShareController::class, 'store'])->name('shares.store');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return redirect()->route('home');
});
