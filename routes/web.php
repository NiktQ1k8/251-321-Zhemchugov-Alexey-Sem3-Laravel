<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/galery/{index}', [MainController::class, 'galery'])
    ->whereNumber('index')
    ->name('galery');

Route::get('/signin', [AuthController::class, 'create'])->name('signin');
Route::post('/signin', [AuthController::class, 'registration'])->name('registration');

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
