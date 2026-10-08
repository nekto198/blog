<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Router;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\PostController;

$router = new Router();

$router->get('/', static function (): void {
    (new HomeController())->index();
});

// More specific route first: /category-slug/post-slug
$router->get('/{categorySlug}/{postSlug}', static function (string $categorySlug, string $postSlug): void {
    (new PostController())->show($categorySlug, $postSlug);
});

$router->get('/{categorySlug}', static function (string $categorySlug): void {
    (new CategoryController())->show($categorySlug);
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
