<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function uri(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $basePath = Config::get('base_path', '');

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        return $path === '' ? '/' : $path;
    }

    // Lê um input de GET/POST já com trim. A sanitização de tipo/formato
    // (e-mail, inteiro, etc.) é feita no Controller, de acordo com o uso.
    public static function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;

        if (is_string($value)) {
            $value = trim($value);
        }

        return $value;
    }

    public static function all(): array
    {
        return self::method() === 'POST' ? $_POST : $_GET;
    }
}
