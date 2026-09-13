<?php

declare(strict_types=1);

namespace App\Helpers;

class Response
{
    public static function json(array $data): never
    {
        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }

    public static function notFound(): never
    {
        http_response_code(404);

        echo '404 - Página não encontrada.';

        exit;
    }
}
