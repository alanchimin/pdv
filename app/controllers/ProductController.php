<?php
namespace App\controllers;

use App\core\ListTrait;
use App\models\Product;
use App\models\Unit;
use App\models\Category;

class ProductController
{
    use ListTrait;

    protected Product $productModel;
    protected Unit $unitModel;
    protected Category $categoryModel;

    public function __construct(?Product $product = null, ?Unit $unit = null, ?Category $category = null)
    {
        $this->productModel = $product ?? new Product();
        $this->unitModel = $unit ?? new Unit();
        $this->categoryModel = $category ?? new Category();
    }

    public function index()
    {
        $categoryId = $_GET['category_id'] ?? null;
        $filters = empty($categoryId) ? [] : [ 'category_id' => $categoryId ];

        $this->list($this->productModel, 'products/index.php', 'products/table.php', 'product', $filters);
    }

    public function form()
    {
        $isUpdate = false;
        $units = $this->unitModel->all();
        $categories = $this->categoryModel->all();
        include __DIR__ . '/../views/products/form.php';
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

        $invalidData = (
            trim($data['name']) === ''
            || empty($data['category_id'])
            || empty($data['unit_id'])
            || empty($data['unit_price'])
            || +$data['unit_price'] <= 0
            || is_numeric($data['discount']) && (+$data['discount'] < 0 || +$data['discount'] > 100)
            || !in_array($data['image_type'], ['url', 'upload'])
        );

        if ($invalidData) {
            $this->json([
                'success' => false,
                'message' => 'Dados inválidos.'
            ], 400);
            return;
        }

        $this->productModel->upsert($data);

        $this->redirect('/product');
    }

    public function edit($id)
    {
        $isUpdate = true;
        $product = $this->productModel->findById((int)$id);

        if (!$product) {
            $this->redirect('/product');
            return;
        }

        $units = $this->unitModel->all();
        $categories = $this->categoryModel->all();
        include __DIR__ . '/../views/products/form.php';
    }

    public function delete($id)
    {
        $this->productModel->delete($id);

        $this->json(['success' => true]);
    }
}
