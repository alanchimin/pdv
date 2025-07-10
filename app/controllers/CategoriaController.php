<?php
namespace App\controllers;

use App\models\Categoria;

class CategoriaController
{
    public function index()
    {
        $search = $_GET['q'] ?? '';
        $currentPage = max(1, (int) ($_GET['pagina'] ?? 1));
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;
        $orderBy = $_GET['ordem'] ?? 'categoria_id';
        $direction = $_GET['direcao'] ?? 'asc';

        $categoriaModel = new Categoria();
        $total = $categoriaModel->count($search);
        $totalPages = ceil($total / $limit);

        $categorias = $categoriaModel->list($search, $limit, $offset, $orderBy, $direction);

        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            include "../views/categorias/table.php";
            exit;
        }

        include "../views/categorias/index.php";
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
            'icone' => $_POST['icone'] ?? null
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome'])) {
            $nome = trim($_POST['nome']);
            $categoria = new Categoria();
            $id = $categoria->upsert(['nome' => $nome]);
            $nova = $categoria->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'categoria' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
