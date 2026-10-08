<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Router;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\PostController;

$router = new Router();

$router->get('/', static function (): void {
    (new HomeController())->index();
});

$router->get('/category/{id}', static function (string $id): void {
    (new CategoryController())->show((int) $id);
});

$router->get('/post/{id}', static function (string $id): void {
    (new PostController())->show((int) $id);
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
