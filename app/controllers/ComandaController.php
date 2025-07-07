<?php
namespace App\controllers;

use App\models\Comanda;

class ComandaController
{
    public function index() {
        $comandaModel = new Comanda();
        $comandas = $comandaModel->all();
        include "../app/views/comandas/index.php";
    }

    public function create() {
        $comandaModel = new Comanda();
        $formasPagamento = $comandaModel->getFormasPagamento();
        include "../app/views/comandas/create.php";
    }

    public function store() {
        $quantidade_total = intval($_POST['quantidade_total'] ?? 0);
        $valor_total = floatval($_POST['valor_total'] ?? 0);
        $forma_pagamento_id = intval($_POST['forma_pagamento_id'] ?? 0);

        if ($quantidade_total > 0 && $valor_total > 0 && $forma_pagamento_id > 0) {
            $comandaModel = new Comanda();
            $comandaModel->create([
                'quantidade_total' => $quantidade_total,
                'valor_total' => $valor_total,
                'forma_pagamento_id' => $forma_pagamento_id
            ]);
            header("Location: index.php?c=comanda");
            exit;
        } else {
            // Poderia redirecionar com erro, aqui só simples:
            header("Location: index.php?c=comanda&a=create&error=1");
            exit;
        }
    }
}
