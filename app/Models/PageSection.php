<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class PageSection
{
    public static function allByPage(int $pageId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM page_sections WHERE page_id = :page_id ORDER BY position ASC');
        $stmt->execute(['page_id' => $pageId]);

        return $stmt->fetchAll();
    }

    public static function deleteByPage(int $pageId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM page_sections WHERE page_id = :page_id');
        $stmt->execute(['page_id' => $pageId]);
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO page_sections (page_id, position, title, content, aside_title, aside_content, image, bg_color, layout)
             VALUES (:page_id, :position, :title, :content, :aside_title, :aside_content, :image, :bg_color, :layout)'
        );
        $stmt->execute([
            'page_id' => $data['page_id'],
            'position' => $data['position'],
            'title' => $data['title'],
            'content' => $data['content'],
            'aside_title' => $data['aside_title'],
            'aside_content' => $data['aside_content'],
            'image' => $data['image'],
            'bg_color' => $data['bg_color'],
            'layout' => $data['layout'],
        ]);

        return (int) $pdo->lastInsertId();
    }
}
