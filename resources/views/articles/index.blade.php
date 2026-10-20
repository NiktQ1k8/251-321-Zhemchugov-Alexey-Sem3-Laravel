@extends('layouts.app')

@section('title', 'Главная')
@section('heading', 'Новости')

@section('content')
    @auth
        <a href="{{ route('articles.create') }}" class="mb-8 inline-block rounded-md bg-blue-700 px-5 py-3 font-medium text-white hover:bg-blue-800">Добавить статью</a>
    @endauth
    <div class="grid gap-8 sm:grid-cols-2">
        @forelse ($articles as $article)
            <article class="wrap-break-word overflow-hidden rounded-lg border border-slate-200 bg-white">
                @if ($article->preview_image && $article->full_image)
                    <a href="{{ route('galery', ['article' => $article]) }}" class="block" aria-label="Открыть изображение: {{ $article->name }}">
                        <img src="{{ asset($article->preview_image) }}" alt="{{ $article->name }}" class="h-48 w-full object-cover">
                    </a>
                @endif
                <div class="space-y-4 p-6">
                    <time datetime="{{ $article->published_at->toDateString() }}" class="block text-sm text-slate-500">{{ $article->published_at->format('d.m.Y') }}</time>
                    <h2 class="text-xl font-semibold text-slate-900">
                        <a href="{{ route('articles.show', $article) }}" class="hover:text-blue-700">{{ $article->name }}</a>
                    </h2>
                    @if ($article->short_desc)
                        <p class="font-medium text-slate-700">{{ $article->short_desc }}</p>
                    @endif
                    <p class="leading-relaxed text-slate-600">{{ $article->body }}</p>
                    <a href="{{ route('articles.show', $article) }}" class="inline-block text-blue-700 underline underline-offset-4 hover:text-blue-900">Читать статью</a>
                </div>
            </article>
        @empty
            <p class="text-lg text-slate-600">Новостей пока нет.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $articles->links() }}</div>
@endsection
