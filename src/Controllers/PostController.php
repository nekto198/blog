<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Post;

class PostController
{
    public function show(int $id): void
    {
        $app = require dirname(__DIR__, 2) . '/config/app.php';
        $postModel = new Post();

        $post = $postModel->findById($id);
        if ($post === null) {
            http_response_code(404);
            echo 'Статья не найдена';
            return;
        }

        $postModel->incrementViews($id);
        $post['views'] = (int) $post['views'] + 1;

        $categories = $postModel->getCategories($id);
        $related = $postModel->getRelated($id, (int) $app['related_posts_limit']);

        (new View())->render('post.tpl', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'categories' => $categories,
            'related' => $related,
        ]);
    }
}
