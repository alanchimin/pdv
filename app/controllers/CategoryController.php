<?php
namespace App\controllers;

use App\core\ListTrait;
use App\models\Category;

class CategoryController
{
    use ListTrait;

    protected Category $model;

    public function __construct(?Category $model = null)
    {
        $this->model = $model ?? new Category();
    }

    public function index()
    {
        $this->list($this->model, 'categories/index.php', 'categories/table.php', 'category');
    }

    public function form()
    {
        $isUpdate = false;
        include __DIR__ . '/../views/categories/form.php';
    }

    public function store()
    {
        $data = [
            'category_id' => $_POST['category_id'] ?? null,
            'name' => $_POST['name'] ?? '',
            'icon' => $_POST['icon'] ?? ''
        ];

        if (trim($data['name']) === '' || trim($data['icon']) === '') {
            $this->json([
                'success' => false,
                'message' => 'Nome e ícone são obrigatórios.'
            ], 400);
            return;
        }

        $this->model->upsert($data);

        $this->redirect('/category');
    }

    public function edit($id)
    {
        $isUpdate = true;
        $category = $this->model->findById((int)$id);

        if (!$category) {
            $this->redirect('/category');
            return;
        }

        include __DIR__ . '/../views/categories/form.php';
    }

    public function delete($id)
    {
        $this->model->delete((int)$id);

        $this->json(['success' => true]);
    }

    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name']) && !empty($_POST['icon'])) {
            $name = trim($_POST['name']);
            $icon = $_POST['icon'];

            $id = $this->model->upsert([
                'category_id' => null,
                'name' => $name,
                'icon' => $icon
            ]);

            $nova = $this->model->findById($id);

            $this->json(['success' => true, 'category' => $nova]);
        } else {
            $this->json(['success' => false, 'message' => 'Dados inválidos'], 400);
        }
    }
}
