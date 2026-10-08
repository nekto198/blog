# Простой блог на PHP + Smarty + MySQL

Учебный блог с категориями и статьями. Без фреймворков: чистый PHP 8.1+, Smarty и MySQL.

## Возможности

- Главная: категории со статьями, по 3 последних поста и кнопка «Все статьи»
- Категория: список, сортировка (дата / просмотры), пагинация по 6 статей
- Статья: HTML-контент, просмотры, похожие, prev/next внутри категории
- ЧПУ: `/{category-slug}` и `/{category-slug}/{post-slug}` (`cocur/slugify`)
- Меню категорий, хлебные крошки, сайдбар на странице статьи
- CLI-сидер категорий, постов и картинок

## Стек

- PHP 8.2 (FPM) + Smarty 5 + MySQL 8 + Nginx
- SCSS → CSS
- Docker Compose
- `cocur/slugify`

## Быстрый старт

```bash
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php database/seed.php
```

Сайт: http://localhost:8088  

MySQL с хоста: `localhost:3308` (user / password / db: `blog` / `blog` / `blog`)

Если порты `8088` или `3308` заняты — поменяйте их в `docker-compose.yml`.

## Стили

```bash
npm install
npm run build:css
```

`scss/main.scss` → `public/assets/css/main.css`

## Структура

```
bootstrap.php # единая точка входа (autoload)
public/       # document root (index.php, assets, uploads)
config/       # app + database
src/          # Router, Config, Database, Controllers, Models, Helpers
templates/    # Smarty
database/     # schema.sql, seed.php
docker/       # PHP и Nginx
scss/         # исходники стилей
```

## Маршруты

| URL | Описание |
|-----|----------|
| `/` | Главная |
| `/{category-slug}?sort=date\|views&page=N` | Категория |
| `/{category-slug}/{post-slug}` | Статья |

## Использование ИИ

При выполнении задания использовался ИИ-ассистент (Cursor) для:

- черновика структуры проекта и Docker-окружения;
- черновика SQL-схемы и сидера;
- вёрстки, SCSS и README;
- отладки и доработок по ходу проверки.

Итоговые решения, структура кода и правки принимались осознанно; коммиты отражают поэтапную разработку.
