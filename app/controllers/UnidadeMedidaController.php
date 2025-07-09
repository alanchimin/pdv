<?php
namespace App\controllers;

use App\models\UnidadeMedida;

class UnidadeMedidaController {
    public function index() {
        $unidades = UnidadeMedida::all();
        include "../views/unidades/index.php";
    }

    public function create() {
        include "../views/unidades/create.php";
    }

    public function store() {
        UnidadeMedida::create($_POST);
        header("Location: /unidadeMedida");
    }

    public function storeAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome']) && !empty($_POST['simbolo'])) {
            $nome = trim($_POST['nome']);
            $simbolo = trim($_POST['simbolo']);

            $unidade = new UnidadeMedida();
            $id = $unidade->create(['nome' => $nome, 'simbolo' => $simbolo]);
            $nova = $unidade->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'unidade' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
