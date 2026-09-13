<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class PageTeamMember
{
    public static function allByPage(int $pageId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM page_team_members WHERE page_id = :page_id ORDER BY position ASC');
        $stmt->execute(['page_id' => $pageId]);

        return $stmt->fetchAll();
    }

    public static function deleteByPage(int $pageId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM page_team_members WHERE page_id = :page_id');
        $stmt->execute(['page_id' => $pageId]);
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO page_team_members (page_id, position, photo, name, role, whatsapp)
             VALUES (:page_id, :position, :photo, :name, :role, :whatsapp)'
        );
        $stmt->execute([
            'page_id' => $data['page_id'],
            'position' => $data['position'],
            'photo' => $data['photo'],
            'name' => $data['name'],
            'role' => $data['role'],
            'whatsapp' => $data['whatsapp'],
        ]);

        return (int) $pdo->lastInsertId();
    }
}
