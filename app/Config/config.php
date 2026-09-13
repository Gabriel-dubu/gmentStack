<?php

declare(strict_types=1);

return [
    // Ajuste conforme a subpasta do seu ambiente (ex: '/gmentStck' no XAMPP/local).
    // Em produção com domínio próprio na raiz, deixe como ''.
    'base_path' => $_ENV['BASE_PATH'] ?? '/gmentStck',

    // true quando estiver servindo via HTTPS (obrigatório em produção)
    'force_https' => filter_var($_ENV['FORCE_HTTPS'] ?? false, FILTER_VALIDATE_BOOLEAN),

    'database' => [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'name' => $_ENV['DB_NAME'] ?? 'gmentstck',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
    ],
];
