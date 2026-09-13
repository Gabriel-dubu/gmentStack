<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    // Renderiza uma view isolada em seu próprio escopo de variáveis,
    // evitando "vazar" o estado do controller para dentro do template.
    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View não encontrada: {$view}");
        }

        // $basePath fica disponível em TODA view automaticamente (usado em
        // header.php, formulários, links de assets), sem precisar repetir
        // isso em cada controller.
        $data['basePath'] ??= Config::get('base_path', '');

        (static function (string $__viewPath, array $__data): void {
            extract($__data, EXTR_SKIP);
            require $__viewPath;
        })($viewPath, $data);
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . Config::get('base_path', '') . $path);
        exit;
    }

    protected function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
