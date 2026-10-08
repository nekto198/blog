# Простой блог на PHP + Smarty + MySQL

Небольшой учебный блог с категориями и статьями. Без фреймворков: чистый PHP 8.1+, Smarty и MySQL.

## Возможности

- Главная: категории со статьями и 3 последних поста в каждой
- Страница категории: список статей, сортировка (дата / просмотры), пагинация
- Страница статьи: полный текст, счётчик просмотров, блок похожих статей
- CLI-сидер категорий и постов

## Стек

- PHP 8.2 (FPM)
- Smarty 5
- MySQL 8
- Nginx
- SCSS (компиляция в CSS)
- Docker Compose

## Быстрый старт (Docker)

```bash
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php database/seed.php
```

Сайт: [http://localhost:8088](http://localhost:8088)

MySQL с хоста: `localhost:3308` (user/password/db: `blog` / `blog` / `blog`)

## Стили

```bash
npm install
npm run build:css
```

Исходники: `scss/main.scss` → `public/assets/css/main.css`

## Структура

```
public/          # document root (index.php, assets, uploads)
config/          # app and database config
src/             # Router, Database, Controllers, Models, View
templates/       # Smarty templates
database/        # schema.sql, seed.php
docker/          # PHP and Nginx configs
scss/            # SCSS sources
```

## Использование ИИ

При выполнении задания использовался ИИ-ассистент (Cursor) для:

- черновика структуры проекта и Docker-окружения;
- черновика SQL-схемы и сидера с тестовыми данными;
- помощи со SCSS и README.

Итоговая архитектура, код моделей/контроллеров, шаблоны и правки принимались и дорабатывались вручную. Коммиты отражают поэтапный ход разработки.

## Маршруты

| URL | Описание |
|-----|----------|
| `/` | Главная |
| `/category/{id}?sort=date\|views&page=N` | Категория |
| `/post/{id}` | Статья |
