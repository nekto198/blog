<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Config;
use App\Models\Category;
use App\Models\Post;
use Smarty\Smarty;

class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(BASE_PATH . '/templates');
        $this->smarty->setCompileDir(BASE_PATH . '/templates_c');
        $this->smarty->setCaching(Smarty::CACHING_OFF);
        $this->smarty->setEscapeHtml(true);

        $this->smarty->assign('appName', Config::get('app.name'));
        $this->smarty->assign('menuCategories', (new Category())->findWithPosts());
        $this->smarty->assign('sidebarPopular', (new Post())->getPopular(5));

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
