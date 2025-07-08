<?php
namespace App\controllers;

use Dompdf\Dompdf;

use App\config\Database;
use App\models\Pedido;
use App\models\Item;
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
        $produtos = $produtoModel->all(limit: 0);

        include "../views/pedidos/index.php";
    }

    public function store() {
        $itens = json_decode($_POST['itens'] ?? '[]', true);

        if (empty($itens)) {
            echo json_encode(['error' => 'Sem itens para finalizar o pedido.']);
            return;
        }

        $pdo = Database::getInstance();

        try {
            $pedidoModel = new Pedido();
            $itemModel = new Item();

            // Inicia transação
            $pdo->beginTransaction();

            // Usa a primeira forma de pagamento disponível (ajuste conforme seu fluxo)
            $formasPagamento = $pedidoModel->getFormasPagamento();
            if (empty($formasPagamento)) {
                throw new \Exception("Nenhuma forma de pagamento cadastrada.");
            }

            $pedido_id = $pedidoModel->create([
                'forma_pagamento_id' => $formasPagamento[0]['forma_pagamento_id']
            ]);

            // Insere os itens do pedido
            foreach ($itens as $item) {
                $itemModel->create([
                    'quantidade' => $item['quantidade'],
                    'desconto_valor' => $item['desconto'],
                    'valor_unitario' => $item['valorUnitario'],
                    'valor_total' => ($item['valorUnitario'] * $item['quantidade']) - $item['desconto'],
                    'produto_id' => $item['produtoId'],
                    'pedido_id' => $pedido_id
                ]);
            }

            $pdo->commit();

            echo json_encode(['success' => true, 'pedido_id' => $pedido_id]);

        } catch (\Exception $e) {
            $pdo->rollBack();
            echo json_encode(['error' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
            return;
        }
    }

    public function getPdfLink($pedido_id) {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->find($pedido_id);

        if (!$pedido || empty($pedido['itens'])) {
            echo json_encode(['error' => 'Pedido não encontrado.']);
            return;
        }

        // Variáveis para a view
        $itens = $pedido['itens'];
        $forma_pagamento = $pedido['forma_pagamento'];

        // Gera o HTML da view
        ob_start();
        include '../views/pedidos/pdf.php';
        $html = ob_get_clean();

        // Gera o PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $filename = 'pedido_' . time() . '.pdf';

        // Caminho físico no servidor/container
        $path = __DIR__ . '/../public/pedidos/' . $filename;

        // Garante que o diretório existe
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Salva o arquivo PDF
        file_put_contents($path, $output);

        // Retorna a URL para o frontend
        header('Content-Type: application/json');
        echo json_encode([
            'url' => '/pedidos/' . $filename
        ]);
    }
}
