@extends('layouts.app')

@section('title', 'Контакты')
@section('heading', 'Контакты')

@section('content')
    <p class="mb-8 text-lg text-slate-600">Связаться с автором и посмотреть исходный код проекта:</p>
    <dl class="max-w-2xl divide-y divide-slate-200">
        @foreach ($contacts as $contact)
            <div class="py-5 first:pt-0">
                <dt class="mb-2 text-sm font-medium text-slate-500">{{ $contact['label'] }}</dt>
                <dd class="wrap-break-word text-lg">
                    @if (isset($contact['href']))
                        <a href="{{ $contact['href'] }}" class="text-blue-700 underline underline-offset-4 hover:text-blue-900">{{ $contact['value'] }}</a>
                    @else
                        {{ $contact['value'] }}
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>
@endsection
