<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Auth;
use App\Core\Request;

/**
 * Uso inverso do AuthMiddleware: se o usuário já está logado,
 * não faz sentido ele ver a tela de login de novo.
 */
class GuestMiddleware
{
    public static function handle(Request $request): bool
    {
        if (!Auth::check()) {
            return true;
        }

        $dashboard = BASE_PATH . '/admin/dashboard';
        header("Location: {$dashboard}");

        return false;
    }
}
