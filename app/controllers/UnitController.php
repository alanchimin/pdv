<?php
namespace App\controllers;

use App\core\ListTrait;
use App\models\Unit;

class UnitController
{
    use ListTrait;

    protected Unit $model;

    public function __construct(?Unit $model = null)
    {
        $this->model = $model ?? new Unit();
    }

    public function index()
    {
        $this->list($this->model, 'units/index.php', 'units/table.php', 'unit');
    }

    public function form()
    {
        $isUpdate = false;
        include __DIR__ . '/../views/units/form.php';
    }

    public function store()
    {
        $data = [
            'unit_id' => $_POST['unit_id'] ?? null,
            'name' => $_POST['name'] ?? '',
            'symbol' => $_POST['symbol'] ?? ''
        ];

        if (trim($data['name']) === '' || trim($data['symbol']) === '') {
            $this->json([
                'success' => false,
                'message' => 'Nome e símbolo são obrigatórios.'
            ], 400);
            return;
        }

        $this->model->upsert($data);

        $this->redirect('/unit');
    }

    public function edit($id)
    {
        $isUpdate = true;
        $unit = $this->model->findById((int)$id);

        if (!$unit) {
            $this->redirect('/unit');
            return;
        }

        include __DIR__ . '/../views/units/form.php';
    }

    public function delete($id)
    {
        $this->model->delete((int)$id);

        $this->json(['success' => true]);
    }

    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name']) && !empty($_POST['symbol'])) {
            $name = trim($_POST['name']);
            $symbol = trim($_POST['symbol']);

            $id = $this->model->upsert([
                'unit_id' => null,
                'name' => $name,
                'symbol' => $symbol
            ]);

            $nova = $this->model->findById($id);

            $this->json(['success' => true, 'unit' => $nova]);
        } else {
            $this->json(['success' => false, 'message' => 'Dados inválidos'], 400);
        }
    }
}
