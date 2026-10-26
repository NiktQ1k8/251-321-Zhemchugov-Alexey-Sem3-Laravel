<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Статистика сайта</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1e293b; line-height: 1.6;">
    <h1 style="font-size: 24px;">{{ config('app.name') }}: статистика за {{ $date }}</h1>
    <p>Данные за текущий день на момент отправки. Часовой пояс: {{ config('app.timezone') }}.</p>
    <ul>
        <li>Просмотры новостей: <strong>{{ $views }}</strong></li>
        <li>Новые комментарии: <strong>{{ $comments }}</strong></li>
    </ul>
    <p>Учитываются все открытия статей, включая повторные, и новые комментарии, в том числе ожидающие модерации.</p>
    <p><a href="{{ route('home') }}">Открыть сайт</a></p>
</body>
</html>
