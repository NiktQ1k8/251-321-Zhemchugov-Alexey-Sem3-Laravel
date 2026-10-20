@extends('layouts.app')

@section('title', 'Вход')
@section('heading', 'Вход')

@section('content')
    <form action="{{ route('authenticate') }}" method="post" class="max-w-md space-y-6">
        @csrf

        <div>
            <label for="email" class="mb-2 block font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="username"
                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-700 focus:outline-2 focus:outline-blue-700"
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')
                <p id="email-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-2 block font-medium">Пароль</label>
            <input id="password" name="password" type="password" required maxlength="255" autocomplete="current-password"
                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-700 focus:outline-2 focus:outline-blue-700"
                @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            @error('password')
                <p id="password-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-md bg-blue-700 px-5 py-3 font-medium text-white hover:bg-blue-800 focus:outline-2 focus:outline-offset-2 focus:outline-blue-700">Войти</button>
        <p class="text-sm text-slate-600">Нет аккаунта? <a href="{{ route('signin') }}" class="text-blue-700 underline underline-offset-4">Зарегистрироваться</a></p>
    </form>
@endsection
