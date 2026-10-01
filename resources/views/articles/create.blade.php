@extends('layouts.app')

@section('title', 'Добавить статью')
@section('heading', 'Добавить статью')

@section('content')
    <form action="{{ route('articles.store') }}" method="post" class="max-w-2xl space-y-6">
        @csrf
        @include('articles._form')
    </form>
@endsection
