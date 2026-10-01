@extends('layouts.app')

@section('title', 'Редактировать статью')
@section('heading', 'Редактировать статью')

@section('content')
    <form action="{{ route('articles.update', $article) }}" method="post" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        @include('articles._form')
    </form>
@endsection
