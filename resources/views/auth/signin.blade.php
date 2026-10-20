@extends('layouts.app')

@section('title', 'Регистрация')
@section('heading', 'Регистрация')

@section('content')
    <form action="{{ route('registration') }}" method="post" class="max-w-md space-y-6">
        @csrf

        <div>
            <label for="name" class="mb-2 block font-medium">Имя</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="255" autocomplete="name"
                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-700 focus:outline-2 focus:outline-blue-700"
                @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
            @error('name')
                <p id="name-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-2 block font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email"
                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-700 focus:outline-2 focus:outline-blue-700"
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')
                <p id="email-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-2 block font-medium">Пароль</label>
            <input id="password" name="password" type="password" required minlength="8" maxlength="255" autocomplete="new-password"
                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-700 focus:outline-2 focus:outline-blue-700"
                @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            <p class="mt-2 text-sm text-slate-500">Не менее 8 символов.</p>
            @error('password')
                <p id="password-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded-md bg-blue-700 px-5 py-3 font-medium text-white hover:bg-blue-800 focus:outline-2 focus:outline-offset-2 focus:outline-blue-700">Зарегистрироваться</button>
        <p class="text-sm text-slate-600">Уже есть аккаунт? <a href="{{ route('login') }}" class="text-blue-700 underline underline-offset-4">Войти</a></p>
    </form>
@endsection
