<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Auth;
use App\Core\Request;

/**
 * Aplique este middleware em toda rota que exige login (painel admin).
 * Retorna true para deixar a requisição seguir, ou já responde
 * o redirecionamento e retorna false para interromper o fluxo.
 */
class AuthMiddleware
{
    public static function handle(Request $request): bool
    {
        if (Auth::check()) {
            return true;
        }

        $login = BASE_PATH . '/admin/login';
        header("Location: {$login}");

        return false;
    }
}
