<?php

declare(strict_types=1);

/**
 * Carrega o arquivo .env (sem depender de composer).
 * Cada linha vira uma variável de ambiente disponível via getenv()/env().
 */
function loadEnv(string $path): void
{
    if (!is_file($path)) {
        throw new RuntimeException(".env não encontrado em: {$path}");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");

        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
    }
}

/**
 * Helper para ler variáveis de ambiente com valor padrão.
 */
function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? getenv($key);

    if ($value === false) {
        return $default;
    }

    return match (strtolower((string) $value)) {
        'true' => true,
        'false' => false,
        default => $value,
    };
}

loadEnv(__DIR__ . '/../.env');

// Caminho base do projeto (útil quando o site fica em subpasta, ex: /gmentStck)
define('BASE_PATH', rtrim((string) env('BASE_PATH', ''), '/'));
define('APP_ROOT', dirname(__DIR__));
define('APP_DEBUG', (bool) env('APP_DEBUG', false));

// Exibição de erros conforme ambiente
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
