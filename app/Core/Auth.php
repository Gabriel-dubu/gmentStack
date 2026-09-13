<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 300; // 5 minutos bloqueado após exceder tentativas

    public static function attempt(string $email, string $password): bool
    {
        if (self::isLocked($email)) {
            return false;
        }

        $user = User::findByEmail($email);

        // Mesma mensagem de erro tanto para "usuário não existe" quanto para
        // "senha errada" (no controller) — evita enumerar e-mails cadastrados.
        if (!$user || !password_verify($password, $user['password'])) {
            self::registerFailedAttempt($email);
            return false;
        }

        self::clearAttempts($email);
        self::login($user);

        return true;
    }

    public static function login(array $user): void
    {
        // Regenera o ID de sessão no login para prevenir session fixation
        session_regenerate_id(true);

        Session::put('user_id', $user['id']);
        Session::put('user_name', $user['name']);
        Session::put('user_role', $user['role'] ?? 'admin');
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => Session::get('user_id'),
            'name' => Session::get('user_name'),
            'role' => Session::get('user_role'),
        ];
    }

    private static function isLocked(string $email): bool
    {
        $data = Session::get(self::attemptsKey($email));

        return $data
            && $data['count'] >= self::MAX_ATTEMPTS
            && (time() - $data['time']) < self::LOCK_SECONDS;
    }

    private static function registerFailedAttempt(string $email): void
    {
        $key = self::attemptsKey($email);
        $data = Session::get($key, ['count' => 0, 'time' => time()]);

        if ((time() - $data['time']) > self::LOCK_SECONDS) {
            $data = ['count' => 0, 'time' => time()];
        }

        $data['count']++;
        $data['time'] = time();
        Session::put($key, $data);
    }

    private static function clearAttempts(string $email): void
    {
        Session::remove(self::attemptsKey($email));
    }

    private static function attemptsKey(string $email): string
    {
        return 'login_attempts_' . md5(strtolower($email));
    }
}
