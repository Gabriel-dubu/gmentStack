<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    /**
     * Renderiza uma view dentro do layout padrão.
     * $data vira variáveis disponíveis dentro do arquivo de view.
     */
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = APP_ROOT . "/app/Views/{$view}.php";

        if (!is_file($viewFile)) {
            throw new \RuntimeException("View não encontrada: {$view}");
        }

        // Captura o conteúdo da view para injetar dentro do layout
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require APP_ROOT . '/app/Views/layout.php';
    }

    /** Renderiza sem layout (ex: para trechos isolados) */
    public static function raw(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require APP_ROOT . "/app/Views/{$view}.php";
    }
}

/**
 * Função global curta para escapar saída e prevenir XSS.
 * Use SEMPRE que for imprimir dado vindo do usuário/banco em HTML.
 */
function e(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}
