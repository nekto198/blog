<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

class Post
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('
            SELECT id, title, description, content, image, views, created_at
            FROM posts
            WHERE id = :id
        ');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $stmt = $this->db->prepare('
            SELECT p.id, p.title, p.description, p.image, p.views, p.created_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            ORDER BY p.created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByCategory(
        int $categoryId,
        string $sort = 'date',
        int $page = 1,
        int $perPage = 6
    ): array {
        $orderBy = $sort === 'views' ? 'p.views DESC' : 'p.created_at DESC';
        $offset = max(0, ($page - 1) * $perPage);

        $stmt = $this->db->prepare("
            SELECT p.id, p.title, p.description, p.image, p.views, p.created_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->db->prepare('
            SELECT COUNT(*)
            FROM post_categories
            WHERE category_id = :category_id
        ');
        $stmt->execute(['category_id' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }

    public function getCategories(int $postId): array
    {
        $stmt = $this->db->prepare('
            SELECT c.id, c.name
            FROM categories c
            INNER JOIN post_categories pc ON pc.category_id = c.id
            WHERE pc.post_id = :post_id
            ORDER BY c.name ASC
        ');
        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll();
    }

    public function incrementViews(int $postId): void
    {
        $stmt = $this->db->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $postId]);
    }

    public function getRelated(int $postId, int $limit = 3): array
    {
        $stmt = $this->db->prepare('
            SELECT DISTINCT p.id, p.title, p.description, p.image, p.views, p.created_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id IN (
                SELECT category_id FROM post_categories WHERE post_id = :post_id
            )
            AND p.id != :post_id2
            ORDER BY p.views DESC, p.created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue('post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue('post_id2', $postId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
