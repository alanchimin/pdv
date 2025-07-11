<?php
namespace App\controllers;

use App\models\UnidadeMedida;

class UnidadeMedidaController
{
    public function index()
    {
        $search = $_GET['q'] ?? '';
        $currentPage = max(1, (int) ($_GET['pagina'] ?? 1));
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;
        $orderBy = $_GET['ordem'] ?? 'unidade_medida_id';
        $direction = $_GET['direcao'] ?? 'asc';

        $unidadeModel = new UnidadeMedida();
        $total = $unidadeModel->count($search);
        $totalPages = ceil($total / $limit);

        $unidades = $unidadeModel->list($search, $limit, $offset, $orderBy, $direction);

        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            include "../views/unidades/table.php";
            exit;
        }

        include "../views/unidades/index.php";
    }

    public function form()
    {
        $isUpdate = false;
        include "../views/unidades/form.php";
    }

    public function store()
    {
        $data = [
            'unidade_medida_id' => $_POST['unidade_medida_id'] ?? null,
            'nome' => $_POST['nome'],
            'simbolo' => $_POST['simbolo']
        ];

        (new UnidadeMedida())->upsert($data);
        header("Location: /unidadeMedida");
        exit;
    }

    public function edit($id)
    {
        $isUpdate = true;
        $unidadeModel = new UnidadeMedida();
        $unidade = $unidadeModel->findById((int)$id);

        if (!$unidade) {
            header('Location: /unidadeMedida');
            exit;
        }

        include "../views/unidades/form.php";
    }

    public function delete($id)
    {
        $unidadeModel = new UnidadeMedida();
        $unidadeModel->delete((int)$id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome']) && !empty($_POST['simbolo'])) {
            $nome = trim($_POST['nome']);
            $simbolo = trim($_POST['simbolo']);

            $unidade = new UnidadeMedida();
            $id = $unidade->upsert(['unidade_medida_id' => null, 'nome' => $nome, 'simbolo' => $simbolo]);
            $nova = $unidade->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'unidade' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
