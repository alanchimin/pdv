<?php
namespace App\controllers;

use App\core\ListagemTrait;
use App\models\UnidadeMedida;

class UnidadeMedidaController
{
    use ListagemTrait;

    public function index()
    {
        $this->listar(new UnidadeMedida(), 'unidades/index.php', 'unidades/table.php', 'unidadeMedida');
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
