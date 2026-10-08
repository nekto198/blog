<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config;
use App\Helpers\View;
use App\Models\Category;
use App\Models\Post;

class HomeController
{
    public function index(): void
    {
        $categoryModel = new Category();
        $postModel = new Post();

        $categories = $categoryModel->findWithPosts();
        $sections = [];
        $limit = (int) Config::get('app.home_posts_per_category');

        foreach ($categories as $category) {
            $sections[] = [
                'category' => $category,
                'posts' => $postModel->getLatestByCategory((int) $category['id'], $limit),
            ];
        }

        (new View())->render('home.tpl', [
            'pageTitle' => 'Главная',
            'sections' => $sections,
        ]);
    }
}
