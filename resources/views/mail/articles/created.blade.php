<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Новая статья</title>
    </head>
    <body style="margin: 0; padding: 24px; background-color: #f8fafc; color: #1e293b; font-family: Arial, sans-serif; line-height: 1.6;">
        <h1 style="font-size: 24px;">На сайте «{{ config('app.name') }}» добавлена статья</h1>
        <h2 style="font-size: 20px; overflow-wrap: anywhere;">{{ $article->name }}</h2>
        <p>Дата публикации: {{ $article->published_at->format('d.m.Y') }}</p>
        @if ($article->short_desc)
            <p><strong>{{ $article->short_desc }}</strong></p>
        @endif
        <div style="white-space: pre-line; overflow-wrap: anywhere;">{{ $article->body }}</div>
        <p><a href="{{ route('articles.show', $article) }}" style="color: #1d4ed8;">Открыть статью на сайте</a></p>
    </body>
</html>
