@extends('layouts.app')

@section('title', 'Редактировать комментарий')
@section('heading', 'Редактировать комментарий')

@section('content')
    <p class="mb-6 wrap-break-word text-slate-600">Статья: {{ $article->name }}</p>

    <form action="{{ route('articles.comments.update', [$article, $comment]) }}" method="post" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        @include('comments._form')
        <a href="{{ route('articles.show', $article) }}#comments" class="inline-block text-blue-700 underline underline-offset-4">Отмена</a>
    </form>
@endsection
