<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class PageStep
{
    public static function allByPage(int $pageId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM page_steps WHERE page_id = :page_id ORDER BY position ASC'
        );
        $stmt->execute(['page_id' => $pageId]);

        return $stmt->fetchAll();
    }

    public static function deleteByPage(int $pageId): void
    {
        $pdo = Database::getConnection();
        $stmt =  $pdo->prepare('DELETE FROM page_steps WHERE page_id = :page_id');
        $stmt->execute(['page_id' => $pageId]);
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO page_steps (page_id, position, title, description) VALUES(:page_id, :position, :title, :description)'
        );
        $stmt->execute([
            'page_id' => $data['page_id'],
            'position' => $data['position'],
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        return (int) $pdo->lastInsertId();
    }
}
