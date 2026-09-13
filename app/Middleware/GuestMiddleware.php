<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Config;

class GuestMiddleware
{
    // Impede que um usuário já logado acesse a tela de login de novo.
    public function handle(): bool
    {
        if (Auth::check()) {
            header('Location: ' . Config::get('base_path', '') . '/admin/dashboard');
            exit;
        }

        return true;
    }
}
