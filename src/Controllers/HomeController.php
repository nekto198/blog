<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Category;
use App\Models\Post;

class HomeController
{
    public function index(): void
    {
        $app = require dirname(__DIR__, 2) . '/config/app.php';
        $categoryModel = new Category();
        $postModel = new Post();

        $categories = $categoryModel->findWithPosts();
        $sections = [];

        foreach ($categories as $category) {
            $sections[] = [
                'category' => $category,
                'posts' => $postModel->getLatestByCategory(
                    (int) $category['id'],
                    (int) $app['home_posts_per_category']
                ),
            ];
        }

        (new View())->render('home.tpl', [
            'pageTitle' => 'Главная',
            'sections' => $sections,
            'breadcrumbs' => [
                ['label' => 'Главная', 'url' => null],
            ],
        ]);
    }
}
