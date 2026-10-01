@extends('layouts.app')

@section('title', 'Главная')
@section('heading', 'Добро пожаловать!')

@section('content')
    <div class="max-w-2xl space-y-4 text-lg leading-relaxed text-slate-600">
        <p>Рады видеть вас на сайте «{{ config('app.name') }}».</p>
        <p>Здесь будут публиковаться новости. Пока вы можете узнать о проекте на странице «О нас» и найти способы связи в разделе «Контакты».</p>
    </div>
@endsection
