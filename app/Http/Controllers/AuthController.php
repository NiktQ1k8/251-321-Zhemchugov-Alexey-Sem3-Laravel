<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.signin');
    }

    public function registration(Request $request): RedirectResponse
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

        User::create($data);

        return redirect()->route('login')->with('status', 'Регистрация завершена. Войдите в свой аккаунт.');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
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

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Неверный email или пароль.',
            ]);
        }

        $request->session()->regenerate();

        // Веб-запросы используют сессию; токен создаётся по условию ЛР №6.
        $token = $request->user()->createToken('web-login', ['*'], now()->addMinutes(config('session.lifetime')));
        $request->session()->put('auth_token_id', $token->accessToken->id);

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->user()->tokens()->whereKey($request->session()->get('auth_token_id'))->delete();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Вы вышли из аккаунта.');
    }
}
