<?php
namespace App\controllers;

use App\models\Produto;
use App\models\UnidadeMedida;
use App\models\Categoria;

class ProdutoController
{
    public function index()
    {
        $busca = $_GET['q'] ?? '';
        $paginaAtual = max(1, (int) ($_GET['pagina'] ?? 1));
        $limite = 10;
        $offset = ($paginaAtual - 1) * $limite;

        $produtoModel = new Produto();
        $totalProdutos = $produtoModel->count($busca);
        $totalPaginas = ceil($totalProdutos / $limite);

        $produtos = $produtoModel->all($busca, $limite, $offset);

        include "../views/produtos/index.php";
    }

    public function create()
    {
        $unidades = (new UnidadeMedida())->all(orderBy: 'nome');
        $categorias = (new Categoria())->all(orderBy: 'nome');
        include "../views/produtos/create.php";
    }

    public function store()
    {
        $imagem_nome = null;

        if ($_POST['tipo_imagem'] === 'upload') {
            if (isset($_FILES['imagem_arquivo']) && $_FILES['imagem_arquivo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagem_arquivo']['name'], PATHINFO_EXTENSION);
                $imagem_nome = uniqid('produto_') . '.' . $ext;
                move_uploaded_file($_FILES['imagem_arquivo']['tmp_name'], __DIR__ . '/../../public/img/' . $imagem_nome);
            }
        } elseif ($_POST['tipo_imagem'] === 'url') {
            $imagem_nome = $_POST['imagem_url'];
        }

        $data = [
            'nome' => $_POST['nome'],
            'imagem' => $imagem_nome,
            'unidade_medida_id' => $_POST['unidade_medida_id'],
            'valor_unitario' => $_POST['valor_unitario'],
            'categoria_id' => $_POST['categoria_id']
        ];

        (new Produto())->create($data);
        header("Location: /produto");
        exit;
    }

    public function buscar()
    {
        $termo = $_GET['q'] ?? '';
        $resultados = (new Produto())->buscar($termo);

        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit;
    }
}
