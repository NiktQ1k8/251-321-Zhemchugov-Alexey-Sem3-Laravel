@extends('layouts.app')

@section('title', e($article->name))
@section('heading', e($article->name))

@section('content')
    <article class="max-w-3xl space-y-6">
        <time datetime="{{ $article->published_at->toDateString() }}" class="block text-sm text-slate-500">{{ $article->published_at->format('d.m.Y') }}</time>
        @if ($article->preview_image && $article->full_image)
            <a href="{{ route('galery', $article) }}" class="block" aria-label="Открыть полное изображение">
                <img src="{{ asset($article->preview_image) }}" alt="{{ $article->name }}" class="h-auto max-w-full rounded-lg">
            </a>
        @endif
        @if ($article->short_desc)
            <p class="font-medium text-slate-700">{{ $article->short_desc }}</p>
        @endif
        <p class="whitespace-pre-line wrap-break-word leading-relaxed text-slate-600">{{ $article->body }}</p>
    </article>

    <div class="mt-8 flex flex-wrap items-center gap-5">
        <a href="{{ route('home') }}" class="text-blue-700 underline underline-offset-4">К списку новостей</a>
        <a href="{{ route('articles.edit', $article) }}" class="text-blue-700 underline underline-offset-4">Редактировать</a>
        <form action="{{ route('articles.destroy', $article) }}" method="post" onsubmit="return confirm('Удалить эту статью?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md bg-red-700 px-4 py-2 font-medium text-white hover:bg-red-800">Удалить</button>
        </form>
    </div>
@endsection
