<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], [
            'required' => 'Поле «:attribute» обязательно.',
            'string' => 'Поле «:attribute» должно быть строкой.',
            'email' => 'Введите корректный email.',
            'unique' => 'Этот email уже зарегистрирован.',
            'min' => 'Поле «:attribute» должно содержать не менее :min символов.',
            'max' => 'Поле «:attribute» должно содержать не более :max символов.',
        ], [
            'name' => 'имя',
            'email' => 'email',
            'password' => 'пароль',
        ]);

        $user = new User($data);
        $user->role()->associate(Role::where('name', Role::READER)->firstOrFail());
        $user->save();

        return response()->json([
            'message' => 'Регистрация завершена. Войдите в свой аккаунт.',
            'user' => $user,
        ], 201);
    }

    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'required' => 'Поле «:attribute» обязательно.',
            'string' => 'Поле «:attribute» должно быть строкой.',
            'email' => 'Введите корректный email.',
            'max' => 'Поле «:attribute» должно содержать не более :max символов.',
        ], [
            'email' => 'email',
            'password' => 'пароль',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Неверный email или пароль.'], 401);
        }

        $expiresAt = now()->addMinutes(config('session.lifetime'));
        $token = $user->createToken('api-login', ['*'], $expiresAt);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Вы вышли из аккаунта.']);
    }
}
