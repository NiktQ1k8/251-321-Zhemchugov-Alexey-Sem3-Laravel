# Новости

Учебный новостной сайт на Laravel. Автор: Жемчугов Алексей Иванович, группа 251-321.

Настройки приложения: название «Новости», локаль ru, резервная локаль en, Faker ru_RU, часовой пояс Europe/Moscow. Значения задаются в .env; пример — .env.example.

## Страницы

- `/` — список новостей из public/articles.json.
- `/galery/{index}` — полное изображение новости; нумерация записей с 0.
- `/about` — информация о проекте.
- `/contacts` — контакты автора из массива в routes/web.php.

Все страницы используют общий Blade-макет resources/views/layouts/app.blade.php с меню и подвалом.

## Окружение

Docker Engine и Docker Compose. PHP 8.4, Composer 2 и Node 24 работают в контейнерах; устанавливать их на компьютер не требуется.

## Первый запуск

Команды выполнять из корня репозитория. Первый запуск загружает образы и зависимости проекта.

```sh
cp .env.example .env
docker compose build
docker compose run --rm --no-deps app composer install
docker compose run --rm --no-deps frontend npm ci
docker compose run --rm --no-deps app php artisan key:generate
docker compose run --rm --no-deps app touch /var/lib/laravel/database.sqlite
docker compose run --rm --no-deps app php artisan migrate
docker compose up -d
```

Копирование .env и генерация ключа нужны при новом развёртывании. Для уже настроенного проекта использовать следующий раздел.

## Обычная работа

```sh
docker compose up -d
```

Открыть [http://localhost:8000](http://localhost:8000). Vite обновляет страницу при сохранении шаблонов и исходников.

Состояние и ошибки:

```sh
docker compose ps
docker compose logs --tail=50
```

Остановка:

```sh
docker compose stop
```

## Команды Laravel

Команды PHP выполнять в работающем сервисе app:

```sh
docker compose exec app php artisan route:list
docker compose exec app php artisan migrate
```

## Данные и файлы

Исходники находятся в репозитории. vendor, node_modules и SQLite — в именованных томах Docker. База внутри app: /var/lib/laravel/database.sqlite. Остановка и docker compose down сохраняют тома; docker compose down -v удаляет зависимости и БД.

Контейнеры используют UID/GID 1000, соответствующие пользователю текущего компьютера. Порты доступны только локально: 8000 — Laravel, 5173 — Vite.
