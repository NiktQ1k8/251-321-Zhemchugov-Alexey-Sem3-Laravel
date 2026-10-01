@extends('layouts.app')

@section('title', 'Главная')
@section('heading', 'Новости')

@section('content')
    <div class="grid gap-8 sm:grid-cols-2">
        @foreach ($articles as $index => $article)
            <article class="overflow-hidden rounded-lg border border-slate-200 bg-white">
                <a href="{{ route('galery', ['index' => $index]) }}" class="block" aria-label="Открыть изображение: {{ $article['name'] }}">
                    <img src="{{ asset($article['preview_image']) }}" alt="{{ $article['name'] }}" class="h-48 w-full object-cover">
                </a>
                <div class="space-y-4 p-6">
                    <p class="text-sm text-slate-500">{{ $article['date'] }}</p>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $article['name'] }}</h2>
                    @isset($article['shortDesc'])
                        <p class="font-medium text-slate-700">{{ $article['shortDesc'] }}</p>
                    @endisset
                    <p class="leading-relaxed text-slate-600">{{ $article['desc'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
@endsection
