<?php
namespace App\controllers;

use App\core\ListTrait;
use App\models\Product;
use App\models\Unit;
use App\models\Category;

class ProductController
{
    use ListTrait;

    public function index()
    {
        $categoryId = $_GET['category_id'] ?? null;
        $filters = empty($categoryId) ? [] : [ 'category_id' => $categoryId ];

        $this->list(new Product(), 'products/index.php', 'products/table.php', 'product', $filters);
    }

    public function form()
    {
        $isUpdate = false;
        $units = (new Unit())->all();
        $categories = (new Category())->all();
        include "../views/products/form.php";
    }

    public function store()
    {
        $image = null;
        $imageType = $_POST['image_type'];

        if ($imageType === 'upload') {
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
                $image = uniqid('product_') . '.' . $ext;
                $path = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $image;

                // Garante que o diretório existe
                if (!is_dir(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }

                move_uploaded_file($_FILES['image_file']['tmp_name'], $path);
            }
        } elseif ($imageType === 'url') {
            $image = $_POST['image_url'] ?: null;
        }

        $data = [
            'product_id' => $_POST['product_id'] ?? null,
            'name' => $_POST['name'],
            'image' => $image,
            'image_type' => $imageType,
            'unit_id' => $_POST['unit_id'],
            'unit_price' => $_POST['unit_price'],
            'discount' => $_POST['discount'] ?: null,
            'category_id' => $_POST['category_id']
        ];

        (new Product())->upsert($data);

        $this->redirect('/product');
    }

    public function edit($id)
    {
        $isUpdate = true;
        $productModel = new Product();
        $product = $productModel->findById((int)$id);

        if (!$product) {
            $this->redirect('/product');
        }

        $units = (new Unit())->all();
        $categories = (new Category())->all();
        include "../views/products/form.php";
    }

    public function delete($id)
    {
        $productModel = new Product();
        $productModel->delete($id);

        $this->json(['success' => true]);
    }
}
