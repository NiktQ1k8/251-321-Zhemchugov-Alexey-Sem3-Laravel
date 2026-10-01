@extends('layouts.app')

@section('title', 'Галерея')
@section('heading', $article['name'])

@section('content')
    <img src="{{ asset($article['full_image']) }}" alt="{{ $article['name'] }}" class="h-auto max-w-full rounded-lg">
    <a href="{{ route('home') }}" class="mt-6 inline-block text-blue-700 underline underline-offset-4 hover:text-blue-900">Вернуться к новостям</a>
@endsection
