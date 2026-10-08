<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Category;
use App\Models\Post;

class CategoryController
{
    public function show(string $slug): void
    {
        $app = require dirname(__DIR__, 2) . '/config/app.php';
        $categoryModel = new Category();
        $postModel = new Post();

        $category = $categoryModel->findBySlug($slug);
        if ($category === null) {
            http_response_code(404);
            echo 'Категория не найдена';
            return;
        }

        $sort = ($_GET['sort'] ?? 'date') === 'views' ? 'views' : 'date';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) $app['posts_per_page'];
        $categoryId = (int) $category['id'];

        $total = $postModel->countByCategory($categoryId);
        $totalPages = max(1, (int) ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $posts = $postModel->getByCategory($categoryId, $sort, $page, $perPage);

        (new View())->render('category.tpl', [
            'pageTitle' => $category['name'],
            'category' => $category,
            'posts' => $posts,
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'breadcrumbs' => [
                ['label' => 'Главная', 'url' => '/'],
                ['label' => $category['name'], 'url' => null],
            ],
        ]);
    }
}
