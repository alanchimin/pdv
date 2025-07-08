<?php
namespace App\controllers;

use App\models\Pedido;
use App\models\Categoria;
use App\models\Produto;


class PedidoController
{
    public function index() {
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->all();

        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->all();

        $produtoModel = new Produto();
        $produtos = $produtoModel->all();

        include "../views/pedidos/index.php";
    }

    public function create() {
        $pedidoModel = new Pedido();
        $formasPagamento = $pedidoModel->getFormasPagamento();
        include "../views/pedidos/create.php";
    }

    public function store() {
        $quantidade_total = intval($_POST['quantidade_total'] ?? 0);
        $valor_total = floatval($_POST['valor_total'] ?? 0);
        $forma_pagamento_id = intval($_POST['forma_pagamento_id'] ?? 0);

        if ($quantidade_total > 0 && $valor_total > 0 && $forma_pagamento_id > 0) {
            $pedidoModel = new Pedido();
            $pedidoModel->create([
                'quantidade_total' => $quantidade_total,
                'valor_total' => $valor_total,
                'forma_pagamento_id' => $forma_pagamento_id
            ]);
            header("Location: /pedido");
            exit;
        } else {
            header("Location: /pedido/create?error=1");
            exit;
        }
    }
}
