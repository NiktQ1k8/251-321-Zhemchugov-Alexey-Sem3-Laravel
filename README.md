# Новости

Учебный новостной сайт на Laravel. Автор: Жемчугов Алексей Иванович, группа 251-321.

Настройки приложения: название «Новости», локаль ru, резервная локаль en, Faker ru_RU, часовой пояс Europe/Moscow. Значения задаются в .env; пример — .env.example.

## Страницы

- `/` и `/articles` — список статей из базы данных по 5 на странице, сначала самые свежие.
- `/articles/create` — создание статьи.
- `/articles/{article}` — чтение статьи, список и форма добавления комментариев; удаление статьи через форму удаляет и её комментарии.
- `/articles/{article}/edit` — редактирование статьи.
- `/articles/{article}/comments/{comment}/edit` — редактирование комментария. Удаление доступно под каждым комментарием на странице статьи.
- `/galery/{article}` — полное изображение статьи; параметр — её ID в базе.
- `/about` — информация о проекте.
- `/contacts` — контакты автора из массива в routes/web.php.
- `/signin` — регистрация: имя, уникальный email, пароль от 8 символов. После сохранения пользователя — переход на форму входа.
- `/login` — вход по email и паролю; успешный вход перенаправляет на главную.
- `POST /logout` — выход через кнопку «Выйти» в меню.

Все страницы используют общий Blade-макет resources/views/layouts/app.blade.php с меню и подвалом.

Чтение новостей и комментариев доступно гостям. При регистрации назначается роль читателя: он может читать новости, добавлять комментарии, редактировать и удалять свои комментарии. Под комментарием показывается имя автора; автор назначается сервером из текущей сессии. Модератор может создавать, редактировать и удалять статьи и любые комментарии; только ему видна ссылка «Создать новость» в меню. Изменяющие маршруты защищены `auth:sanctum` и проверками Gate/ArticlePolicy. При нехватке прав сайт возвращает 403 с русским пояснением.

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
docker compose run --rm --no-deps app php artisan db:seed
docker compose up -d
```

Копирование .env и генерация ключа нужны при новом развёртывании. Seeder создаёт роли читателя/модератора, учебного модератора с email `moderator@example.test`, 10 статей и по 3 комментария к каждой. Фабрика создаёт отдельного учебного читателя для каждого комментария. Случайный пароль нового модератора выводится в терминал один раз; сохраните его. Повторный запуск сохраняет роли, модератора и его пароль, добавляя ещё 10 статей, 30 комментариев и их авторов. Для уже настроенного проекта использовать следующий раздел.

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

Добавить роли и модератора к существующей БД без создания новых статей:

```sh
docker compose exec app php artisan db:seed --class=RoleSeeder
docker compose exec app php artisan db:seed --class=ModeratorSeeder
```

RoleSeeder назначает пользователям без роли роль читателя. ModeratorSeeder создаёт отдельный аккаунт и не меняет пароль существующего модератора; если его email занят читателем, seeder останавливается с пояснением.

## Данные и файлы

Исходники находятся в репозитории. vendor, node_modules и SQLite — в именованных томах Docker. База внутри app: /var/lib/laravel/database.sqlite. Остановка и docker compose down сохраняют тома; docker compose down -v удаляет зависимости и БД.

Контейнеры используют UID/GID 1000, соответствующие пользователю текущего компьютера. Порты доступны только локально: 8000 — Laravel, 5173 — Vite.
