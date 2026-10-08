<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Database;
use App\Router;

// Ensure database config is loadable
Database::class;

$router = new Router();

$router->get('/', static function (): void {
    echo 'Home';
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
