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
        @can('update', $article)
            <a href="{{ route('articles.edit', $article) }}" class="text-blue-700 underline underline-offset-4">Редактировать</a>
        @endcan
        @can('delete', $article)
            <form action="{{ route('articles.destroy', $article) }}" method="post" onsubmit="return confirm('Удалить статью и все её комментарии?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-md bg-red-700 px-4 py-2 font-medium text-white hover:bg-red-800">Удалить</button>
            </form>
        @endcan
    </div>

    <section id="comments" class="mt-10 max-w-3xl space-y-6 border-t border-slate-200 pt-8">
        <h2 class="text-2xl font-semibold">Комментарии ({{ $article->comments->count() }})</h2>

        @forelse ($article->comments as $comment)
            <article class="space-y-3 rounded-lg border border-slate-200 bg-white p-5">
                <time datetime="{{ $comment->created_at->toIso8601String() }}" class="text-sm text-slate-500">{{ $comment->created_at->format('d.m.Y H:i') }}</time>
                <p class="whitespace-pre-line wrap-break-word text-slate-700">{{ $comment->body }}</p>
                <div class="flex flex-wrap items-center gap-4">
                    @can('update-comment', $comment)
                        <a href="{{ route('articles.comments.edit', [$article, $comment]) }}" class="text-blue-700 underline underline-offset-4">Редактировать комментарий</a>
                    @endcan
                    @can('delete-comment', $comment)
                        <form action="{{ route('articles.comments.destroy', [$article, $comment]) }}" method="post" onsubmit="return confirm('Удалить этот комментарий?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-700 underline underline-offset-4">Удалить комментарий</button>
                        </form>
                    @endcan
                </div>
            </article>
        @empty
            <p class="text-slate-600">Комментариев пока нет.</p>
        @endforelse

        @can('create-comment')
            <h3 class="text-xl font-semibold">Добавить комментарий</h3>
            <form action="{{ route('articles.comments.store', $article) }}" method="post" class="space-y-4">
                @csrf
                @include('comments._form', ['comment' => new \App\Models\Comment])
            </form>
        @else
            @guest
                <p class="text-slate-600"><a href="{{ route('login') }}" class="text-blue-700 underline underline-offset-4">Войдите</a>, чтобы добавить комментарий.</p>
            @endguest
        @endcan
    </section>
@endsection
