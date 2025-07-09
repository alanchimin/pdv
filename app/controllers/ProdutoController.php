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
        $ordem = $_GET['ordem'] ?? 'produto_id';
        $direcao = $_GET['direcao'] ?? 'desc';

        $produtoModel = new Produto();
        $totalProdutos = $produtoModel->count($busca);
        $totalPaginas = ceil($totalProdutos / $limite);

        $produtos = $produtoModel->all($busca, $limite, $offset, $ordem, $direcao);

        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            include "../views/produtos/tabela.php";
            exit;
        }

        include "../views/produtos/index.php";
    }

    public function create()
    {
        $unidades = (new UnidadeMedida())->all();
        $categorias = (new Categoria())->all();
        include "../views/produtos/create.php";
    }

    public function store()
    {
        $imagem_nome = null;

        if ($_POST['tipo_imagem'] === 'upload') {
            if (isset($_FILES['imagem_arquivo']) && $_FILES['imagem_arquivo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagem_arquivo']['name'], PATHINFO_EXTENSION);
                $imagem_nome = uniqid('produto_') . '.' . $ext;
                move_uploaded_file($_FILES['imagem_arquivo']['tmp_name'], __DIR__ . '/../../public/upload/' . $imagem_nome);
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
}
