<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config;
use App\Helpers\View;
use App\Models\Category;
use App\Models\Post;

class PostController
{
    public function show(string $categorySlug, string $postSlug): void
    {
        $categoryModel = new Category();
        $postModel = new Post();

        $category = $categoryModel->findBySlug($categorySlug);
        $post = $postModel->findBySlug($postSlug);

        if (
            $category === null
            || $post === null
            || !$postModel->belongsToCategory((int) $post['id'], (int) $category['id'])
        ) {
            http_response_code(404);
            echo 'Статья не найдена';
            return;
        }

        $postId = (int) $post['id'];
        $postModel->incrementViews($postId);
        $post['views'] = (int) $post['views'] + 1;

        $categories = $postModel->getCategories($postId);
        $related = $postModel->getRelated(
            $postId,
            (int) Config::get('app.related_posts_limit')
        );
        $adjacent = $postModel->getAdjacentInCategory(
            $postId,
            (int) $category['id'],
            (string) $post['created_at']
        );

        (new View())->render('post.tpl', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'category' => $category,
            'categories' => $categories,
            'related' => $related,
            'prevPost' => $adjacent['previous'],
            'nextPost' => $adjacent['next'],
            'breadcrumbs' => [
                ['label' => 'Главная', 'url' => '/'],
                ['label' => $category['name'], 'url' => '/' . $category['slug']],
                ['label' => $post['title'], 'url' => null],
            ],
        ]);
    }
}
