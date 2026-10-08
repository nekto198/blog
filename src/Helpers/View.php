<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Category;
use Smarty\Smarty;

class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);

        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($root . '/templates');
        $this->smarty->setCompileDir($root . '/templates_c');
        $this->smarty->setCaching(Smarty::CACHING_OFF);
        $this->smarty->setEscapeHtml(true);

        $app = require $root . '/config/app.php';
        $this->smarty->assign('appName', $app['name']);
        $this->smarty->assign('menuCategories', (new Category())->findWithPosts());

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';
        $segments = $path === '/' ? [] : explode('/', ltrim($path, '/'));

        $this->smarty->assign('isHome', $path === '/');
        $this->smarty->assign('activeCategorySlug', $segments[0] ?? null);
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}
