<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    private const TIMEOUT_SECONDS = 1800; // 30 minutos de inatividade

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => (bool) Config::get('force_https', false),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > self::TIMEOUT_SECONDS) {
            self::destroy();
            session_start();
        }

        $_SESSION['last_activity'] = time();
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    // Guarda uma mensagem de "leitura única" (ex: erro de login) ou a recupera e apaga
    public static function flash(string $key, mixed $value = null): mixed
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }

        $data = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return $data;
    }
}
