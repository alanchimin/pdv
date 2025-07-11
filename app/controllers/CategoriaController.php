<?php
namespace App\controllers;

use App\core\ListagemTrait;
use App\models\Categoria;

class CategoriaController
{
    use ListagemTrait;

    public function index()
    {
        $this->listar(new Categoria(), 'categorias/index.php', 'categorias/table.php', 'categoria');
    }

    public function form()
    {
        $isUpdate = false;
        include "../views/categorias/form.php";
    }

    public function store()
    {
        $data = [
            'categoria_id' => $_POST['categoria_id'] ?? null,
            'nome' => $_POST['nome'],
            'icone' => $_POST['icone']
        ];

        (new Categoria())->upsert($data);
        header("Location: /categoria");
        exit;
    }

    public function edit($id)
    {
        $isUpdate = true;
        $categoriaModel = new Categoria();
        $categoria = $categoriaModel->findById((int)$id);

        if (!$categoria) {
            header('Location: /categoria');
            exit;
        }

        include "../views/categorias/form.php";
    }

    public function delete($id)
    {
        $categoriaModel = new Categoria();
        $categoriaModel->delete((int)$id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome']) && !empty($_POST['icone'])) {
            $nome = trim($_POST['nome']);
            $icone = $_POST['icone'];
            $categoria = new Categoria();
            $id = $categoria->upsert(['categoria_id' => null, 'nome' => $nome, 'icone' => $icone]);
            $nova = $categoria->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'categoria' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
