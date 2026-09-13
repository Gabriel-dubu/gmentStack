<?php

declare(strict_types=1);

namespace App\Helpers;

class Redirect
{
    public static function to(string $path): never
    {
        header('Location: ' . BASE_PATH . $path);
        exit;
    }
}
