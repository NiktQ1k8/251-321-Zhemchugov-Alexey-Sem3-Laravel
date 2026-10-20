@extends('layouts.app')

@section('title', 'Доступ запрещён')
@section('heading', 'Доступ запрещён')

@section('content')
    <p role="alert" class="mb-6 text-slate-700">{{ $message }}</p>
    <a href="{{ route('home') }}" class="text-blue-700 underline underline-offset-4">Вернуться к новостям</a>
@endsection
