<?php

declare(strict_types=1);

namespace App\Core;

class Config
{
    private static array $items = [];

    public static function load(string $path): void
    {
        self::$items = require $path;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$items[$key] ?? $default;
    }
}
