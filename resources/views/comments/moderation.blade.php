@extends('layouts.app')

@section('title', 'Модерация комментариев')
@section('heading', 'Модерация комментариев')

@section('content')
    <p class="mb-6 text-slate-600">Комментарии, ожидающие проверки. После одобрения комментарий появится под статьёй. Отклонённый комментарий будет удалён.</p>

    <div class="max-w-3xl space-y-6">
        @forelse ($comments as $comment)
            <article class="space-y-3 rounded-lg border border-slate-200 bg-white p-5">
                <p class="wrap-break-word">Статья: <a href="{{ route('articles.show', $comment->article) }}#comments" class="text-blue-700 underline underline-offset-4">{{ $comment->article->name }}</a></p>
                <p class="wrap-break-word font-medium">Автор: {{ $comment->user?->name ?? 'Автор неизвестен' }}</p>
                <time datetime="{{ $comment->created_at->toIso8601String() }}" class="text-sm text-slate-500">{{ $comment->created_at->format('d.m.Y H:i') }}</time>
                <p class="whitespace-pre-line wrap-break-word text-slate-700">{{ $comment->body }}</p>
                <div class="flex flex-wrap items-center gap-4">
                    <form action="{{ route('comments.approve', $comment) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">Принять</button>
                    </form>
                    <form action="{{ route('comments.reject', $comment) }}" method="post" onsubmit="return confirm('Отклонить и удалить этот комментарий?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md bg-red-700 px-4 py-2 font-medium text-white hover:bg-red-800">Отклонить</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="text-slate-600">Нет комментариев, ожидающих модерации.</p>
        @endforelse
    </div>
@endsection
