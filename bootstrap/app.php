<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Session;

// ---- Autoload PSR-4 simples (App\* -> app/*.php), sem depender do Composer ----
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

require __DIR__ . '/../app/Core/helpers.php';

// ---- Carrega variáveis do .env (versão simples, sem dependências) ----
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $_ENV[trim($key)] = trim($value);
    }
}

Config::load(__DIR__ . '/../app/Config/config.php');

// ---- Erros: nunca exibir na tela, sempre logar em arquivo ----
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');

Session::start();

// ---- Cabeçalhos básicos de segurança ----
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
