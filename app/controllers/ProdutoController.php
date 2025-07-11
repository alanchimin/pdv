<?php
namespace App\controllers;

use App\core\ListagemTrait;
use App\models\Produto;
use App\models\UnidadeMedida;
use App\models\Categoria;

class ProdutoController
{
    use ListagemTrait;

    public function index()
    {
        $categoryId = $_GET['categoria_id'] ?? null;
        $filters = empty($categoryId) ? [] : [ 'categoria_id' => $categoryId ];

        $this->listar(new Produto(), 'produtos/index.php', 'produtos/table.php', 'produto', $filters);
    }

    public function form()
    {
        $isUpdate = false;
        $unidades = (new UnidadeMedida())->all();
        $categorias = (new Categoria())->all();
        include "../views/produtos/form.php";
    }

    public function store()
    {
        $imagem_nome = null;
        $tipo_imagem = $_POST['tipo_imagem'];

        if ($tipo_imagem === 'upload') {
            if (isset($_FILES['imagem_arquivo']) && $_FILES['imagem_arquivo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['imagem_arquivo']['name'], PATHINFO_EXTENSION);
                $imagem_nome = uniqid('produto_') . '.' . $ext;
                $path = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $imagem_nome;

                // Garante que o diretório existe
                if (!is_dir(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }

                move_uploaded_file($_FILES['imagem_arquivo']['tmp_name'], $path);
            }
        } elseif ($tipo_imagem === 'url') {
            $imagem_nome = $_POST['imagem_url'] ?: null;
        }

        $data = [
            'produto_id' => $_POST['produto_id'] ?? null,
            'nome' => $_POST['nome'],
            'imagem' => $imagem_nome,
            'tipo_imagem' => $tipo_imagem,
            'unidade_medida_id' => $_POST['unidade_medida_id'],
            'valor_unitario' => $_POST['valor_unitario'],
            'desconto' => $_POST['desconto'] ?: null,
            'categoria_id' => $_POST['categoria_id']
        ];

        (new Produto())->upsert($data);
        header("Location: /produto");
        exit;
    }

    public function edit($id)
    {
        $isUpdate = true;
        $produtoModel = new Produto();
        $produto = $produtoModel->findById((int)$id);

        if (!$produto) {
            header('Location: /produto');
            exit;
        }

        $unidades = (new UnidadeMedida())->all();
        $categorias = (new Categoria())->all();
        include "../views/produtos/form.php";
    }

    public function delete($id)
    {
        $produtoModel = new Produto();
        $produtoModel->delete($id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}
