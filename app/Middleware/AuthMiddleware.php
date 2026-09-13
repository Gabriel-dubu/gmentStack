<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Config;
use App\Core\Session;

class AuthMiddleware
{
    // Protege rotas que exigem login (ex: /admin/dashboard).
    public function handle(): bool
    {
        if (!Auth::check()) {
            Session::flash('error', 'Você precisa estar logado para acessar essa página.');
            header('Location: ' . Config::get('base_path', '') . '/admin/login');
            exit;
        }

        return true;
    }
}
