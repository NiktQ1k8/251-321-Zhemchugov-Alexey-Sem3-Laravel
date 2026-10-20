<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentModerationController;
use Illuminate\Support\Facades\Route;

Route::pattern('article', '[0-9]+');
Route::pattern('comment', '[0-9]+');

Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::resource('articles', ArticleController::class)
    ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'auth:sanctum');

Route::resource('articles.comments', CommentController::class)
    ->only(['store', 'edit', 'update', 'destroy'])
    ->scoped()
    ->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'can:moderate-comments'])->group(function () {
    Route::get('/moderation/comments', [CommentModerationController::class, 'index'])->name('comments.moderation');
    Route::patch('/moderation/comments/{comment}/approve', [CommentModerationController::class, 'approve'])->name('comments.approve');
    Route::delete('/moderation/comments/{comment}/reject', [CommentModerationController::class, 'reject'])->name('comments.reject');
});

Route::get('/galery/{article}', [ArticleController::class, 'galery'])
    ->whereNumber('article')
    ->name('galery');

Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthController::class, 'create'])->name('signin');
    Route::post('/signin', [AuthController::class, 'registration'])->name('registration');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:6,1')->name('authenticate');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    $contacts = [
        [
            'label' => 'Автор',
            'value' => 'Жемчугов Алексей Иванович',
        ],
        [
            'label' => 'Электронная почта',
            'value' => 'zhemchugov.alex@gmail.com',
            'href' => 'mailto:zhemchugov.alex@gmail.com',
        ],
        [
            'label' => 'Репозиторий проекта',
            'value' => 'NiktQ1k8/251-321-Zhemchugov-Alexey-Sem3-Laravel',
            'href' => 'https://github.com/NiktQ1k8/251-321-Zhemchugov-Alexey-Sem3-Laravel',
        ],
    ];

    return view('contacts', ['contacts' => $contacts]);
})->name('contacts');
