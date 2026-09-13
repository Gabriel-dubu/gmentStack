<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Lead
{
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO leads (page_id, name, company, phone, email, 
             situation, message, created_at)
             VALUES (:page_id, :name, :company, :phone, :email, :situation, :message, NOW())'
        );

        $stmt->execute([
            'page_id' => $data['page_id'],
            'name' => $data['name'],
            'company' => $data['company'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'situation' => $data['situation'],
            'message' => $data['message'],
        ]);

        return (int) $pdo->lastInsertId();
    }

    // JOIN com pages só pra mostrar de qual página o lead veio, na listagem do admin.
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query(
            'SELECT leads.*, pages.title AS page_title
             FROM leads
             LEFT JOIN pages ON pages.id = leads.page_id
             ORDER BY leads.created_at DESC'
        );

        return $stmt->fetchAll();
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM leads WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
