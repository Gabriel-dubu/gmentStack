<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class User
{
    // SELECT com prepared statement (placeholder nomeado) — o valor de $email
    // nunca é concatenado na query, então injeção de SQL não tem como ocorrer aqui.
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function create(string $name, string $email, string $password, string $role = 'admin'): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password, role, created_at) VALUES (:name, :email, :password, :role, NOW())'
        );

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_ARGON2ID),
            'role' => $role,
        ]);

        return (int) $pdo->lastInsertId();
    }
}
