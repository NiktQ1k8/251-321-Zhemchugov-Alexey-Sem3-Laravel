<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::pattern('article', '[0-9]+');

Route::name('api.')->group(function () {
    Route::post('/signin', [AuthController::class, 'registration'])->name('registration');
    Route::post('/login', [AuthController::class, 'authenticate'])
        ->middleware('throttle:6,1')->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')->name('logout');

    Route::apiResource('articles', ArticleController::class)
        ->middlewareFor('show', 'record.article.view')
        ->middlewareFor(['store', 'update', 'destroy'], 'auth:sanctum');

    Route::get('/galery/{article}', [ArticleController::class, 'galery'])->name('galery');
});
