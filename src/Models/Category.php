<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, slug, description FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, slug, description FROM categories WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Categories that have at least one post.
     */
    public function findWithPosts(): array
    {
        $sql = '
            SELECT c.id, c.name, c.slug, c.description, COUNT(pc.post_id) AS posts_count
            FROM categories c
            INNER JOIN post_categories pc ON pc.category_id = c.id
            GROUP BY c.id, c.name, c.slug, c.description
            ORDER BY c.name ASC
        ';

        return $this->db->query($sql)->fetchAll();
    }
}
