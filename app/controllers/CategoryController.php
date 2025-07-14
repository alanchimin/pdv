<?php
namespace App\controllers;

use App\core\ListTrait;
use App\models\Category;

class CategoryController
{
    use ListTrait;

    public function index()
    {
        $this->list(new Category(), 'categories/index.php', 'categories/table.php', 'category');
    }

    public function form()
    {
        $isUpdate = false;
        include "../views/categories/form.php";
    }

    public function store()
    {
        $data = [
            'category_id' => $_POST['category_id'] ?? null,
            'name' => $_POST['name'],
            'icon' => $_POST['icon']
        ];

        (new Category())->upsert($data);
        $this->redirect('/category');
    }

    public function edit($id)
    {
        $isUpdate = true;
        $categoryModel = new Category();
        $category = $categoryModel->findById((int)$id);

        if (!$category) {
            $this->redirect('/category');
        }

        include "../views/categories/form.php";
    }

    public function delete($id)
    {
        $categoryModel = new Category();
        $categoryModel->delete((int)$id);

        $this->json(['success' => true]);
    }

    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name']) && !empty($_POST['icon'])) {
            $name = trim($_POST['name']);
            $icon = $_POST['icon'];
            $category = new Category();
            $id = $category->upsert(['category_id' => null, 'name' => $name, 'icon' => $icon]);
            $nova = $category->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'category' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
