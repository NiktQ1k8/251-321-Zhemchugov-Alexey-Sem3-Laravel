<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') — {{ config('app.name') }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-slate-50 font-sans text-slate-800">
        <div id="article-notifications"></div>
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-4 px-6 py-5">
                <a href="{{ route('home') }}" class="text-xl font-semibold text-slate-900">
                    {{ config('app.name') }}
                </a>
                <nav aria-label="Основная навигация" class="relative flex flex-wrap items-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-blue-700" @if(request()->routeIs('home')) aria-current="page" @endif>Главная</a>
                    <a href="{{ route('about') }}" class="hover:text-blue-700" @if(request()->routeIs('about')) aria-current="page" @endif>О нас</a>
                    <a href="{{ route('contacts') }}" class="hover:text-blue-700" @if(request()->routeIs('contacts')) aria-current="page" @endif>Контакты</a>
                    @include('notifications._dropdown')
                    @can('create', \App\Models\Article::class)
                        <a href="{{ route('articles.create') }}" class="hover:text-blue-700" @if(request()->routeIs('articles.create')) aria-current="page" @endif>Создать новость</a>
                    @endcan
                    @can('moderate-comments')
                        <a href="{{ route('comments.moderation') }}" class="hover:text-blue-700" @if(request()->routeIs('comments.*')) aria-current="page" @endif>Модерация комментариев</a>
                    @endcan
                    @guest
                        <a href="{{ route('signin') }}" class="hover:text-blue-700" @if(request()->routeIs('signin')) aria-current="page" @endif>Регистрация</a>
                        <a href="{{ route('login') }}" class="hover:text-blue-700" @if(request()->routeIs('login')) aria-current="page" @endif>Вход</a>
                    @else
                        <span class="max-w-48 wrap-break-word text-slate-600">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="hover:text-blue-700">Выйти</button>
                        </form>
                    @endguest
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-12 sm:py-20">
            <h1 class="mb-6 wrap-break-word text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">@yield('heading')</h1>
            @if (session('status'))
                <p role="status" class="mb-6 rounded-md bg-green-100 px-4 py-3 text-green-800">{{ session('status') }}</p>
            @endif
            @if (session('warning'))
                <p role="alert" class="mb-6 rounded-md bg-amber-100 px-4 py-3 text-amber-900">{{ session('warning') }}</p>
            @endif
            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-5xl px-6 py-5 text-sm text-slate-600">
                Жемчугов Алексей Иванович · Группа 251-321
            </div>
        </footer>
    </body>
</html>
