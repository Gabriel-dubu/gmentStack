<?php

declare(strict_types=1);

namespace App\Helpers;

class Url
{
    public static function to(string $path = ''): string
    {
        return rtrim((string) env('APP_URL'), '/') . $path;
    }

    public static function asset(string $path): string
    {
        return self::to('/assets/' . ltrim($path, '/'));
    }
}
