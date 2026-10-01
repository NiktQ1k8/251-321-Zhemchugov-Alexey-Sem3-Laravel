@extends('layouts.app')

@section('title', 'О нас')
@section('heading', 'О нас')

@section('content')
    <div class="max-w-2xl space-y-4 text-lg leading-relaxed text-slate-600">
        <p>«{{ config('app.name') }}» — учебный новостной сайт на Laravel.</p>
        <p>Проект создаётся поэтапно в рамках лабораторных работ. Здесь появятся новости, а читатели смогут обсуждать публикации в комментариях.</p>
        <p>Автор проекта — Жемчугов Алексей Иванович, студент группы 251-321.</p>
    </div>
@endsection
