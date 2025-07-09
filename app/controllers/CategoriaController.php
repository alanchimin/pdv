<?php
namespace App\controllers;

use App\models\Categoria;

class CategoriaController {
    public function index() {
        $categorias = Categoria::all();
        include "../views/categorias/index.php";
    }

    public function create() {
        include "../views/categorias/create.php";
    }

    public function store() {
        Categoria::create($_POST);
        header("Location: /categoria");
    }

    public function storeAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome'])) {
            $nome = trim($_POST['nome']);
            $categoria = new Categoria();
            $id = $categoria->create(['nome' => $nome]);

            $nova = $categoria->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'categoria' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
