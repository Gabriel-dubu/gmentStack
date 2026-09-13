<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    // {id} chega aqui como string vinda da URL — sempre convertemos/validamos
    // antes de usar, nunca confiamos no tipo bruto vindo da rota.
    public function produto(string $id): void
    {
        if (!ctype_digit($id)) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $this->view('home/produto', ['id' => (int) $id]);
    }
}
