<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.signin');
    }

    public function registration(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], [
            'required' => 'Поле «:attribute» обязательно.',
            'string' => 'Поле «:attribute» должно быть строкой.',
            'email' => 'Введите корректный email.',
            'min' => 'Поле «:attribute» должно содержать не менее :min символов.',
            'max' => 'Поле «:attribute» должно содержать не более :max символов.',
        ], [
            'name' => 'имя',
            'email' => 'email',
            'password' => 'пароль',
        ]);

        return response()->json([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }
}
