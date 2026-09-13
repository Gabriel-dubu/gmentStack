<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;

class ProdutoController
{
    public function show(Request $request): void
    {
        // Todo dado vindo de rota/POST/GET deve ser tratado como não confiável.
        // Aqui validamos que o id é numérico antes de usar em qualquer consulta.
        $id = $request->param('id');

        if (!ctype_digit((string) $id)) {
            http_response_code(400);
            echo 'ID de produto inválido.';
            return;
        }

        // Exemplo: aqui você buscaria o produto no banco com prepared statement,
        // igual foi feito em App\Models\User::findByEmail()

        View::render('produto', [
            'titulo' => 'Produto',
            'id'     => (int) $id,
        ]);
    }
}
