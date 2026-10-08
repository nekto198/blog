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

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('
            SELECT id, title, slug, description, content, image, views, created_at
            FROM posts
            WHERE slug = :slug
        ');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function belongsToCategory(int $postId, int $categoryId): bool
    {
        $stmt = $this->db->prepare('
            SELECT 1
            FROM post_categories
            WHERE post_id = :post_id AND category_id = :category_id
            LIMIT 1
        ');
        $stmt->execute([
            'post_id' => $postId,
            'category_id' => $categoryId,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $stmt = $this->db->prepare('
            SELECT p.id, p.title, p.slug, p.description, p.image, p.views, p.created_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            ORDER BY p.created_at DESC, p.id DESC
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
        $orderBy = $sort === 'views'
            ? 'p.views DESC, p.created_at DESC, p.id DESC'
            : 'p.created_at DESC, p.id DESC';
        $offset = max(0, ($page - 1) * $perPage);

        $stmt = $this->db->prepare("
            SELECT p.id, p.title, p.slug, p.description, p.image, p.views, p.created_at
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
            SELECT c.id, c.name, c.slug
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

    /**
     * Adjacent posts in a category by publication date.
     * previous = older, next = newer.
     *
     * @return array{previous: ?array, next: ?array}
     */
    public function getAdjacentInCategory(int $postId, int $categoryId, string $createdAt): array
    {
        $previousStmt = $this->db->prepare('
            SELECT p.id, p.title, p.slug, p.created_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
              AND (
                  p.created_at < :created_at
                  OR (p.created_at = :created_at2 AND p.id < :post_id)
              )
            ORDER BY p.created_at DESC, p.id DESC
            LIMIT 1
        ');
        $previousStmt->execute([
            'category_id' => $categoryId,
            'created_at' => $createdAt,
            'created_at2' => $createdAt,
            'post_id' => $postId,
        ]);
        $previous = $previousStmt->fetch() ?: null;

        $nextStmt = $this->db->prepare('
            SELECT p.id, p.title, p.slug, p.created_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
              AND (
                  p.created_at > :created_at
                  OR (p.created_at = :created_at2 AND p.id > :post_id)
              )
            ORDER BY p.created_at ASC, p.id ASC
            LIMIT 1
        ');
        $nextStmt->execute([
            'category_id' => $categoryId,
            'created_at' => $createdAt,
            'created_at2' => $createdAt,
            'post_id' => $postId,
        ]);
        $next = $nextStmt->fetch() ?: null;

        return [
            'previous' => $previous,
            'next' => $next,
        ];
    }

    /**
     * Related posts with a primary category slug for building URLs.
     */
    public function getRelated(int $postId, int $limit = 3): array
    {
        $stmt = $this->db->prepare('
            SELECT
                p.id,
                p.title,
                p.slug,
                p.description,
                p.image,
                p.views,
                p.created_at,
                (
                    SELECT c.slug
                    FROM categories c
                    INNER JOIN post_categories pc2 ON pc2.category_id = c.id
                    WHERE pc2.post_id = p.id
                    ORDER BY c.name ASC
                    LIMIT 1
                ) AS category_slug
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE pc.category_id IN (
                SELECT category_id FROM post_categories WHERE post_id = :post_id
            )
            AND p.id != :post_id2
            GROUP BY p.id, p.title, p.slug, p.description, p.image, p.views, p.created_at
            ORDER BY p.created_at DESC, p.id DESC
            LIMIT :limit
        ');
        $stmt->bindValue('post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue('post_id2', $postId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
