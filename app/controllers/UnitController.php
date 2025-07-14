<?php
namespace App\controllers;

use App\core\ListTrait;
use App\models\Unit;

class UnitController
{
    use ListTrait;

    public function index()
    {
        $this->list(new Unit(), 'units/index.php', 'units/table.php', 'unit');
    }

    public function form()
    {
        $isUpdate = false;
        include "../views/units/form.php";
    }

    public function store()
    {
        $data = [
            'unit_id' => $_POST['unit_id'] ?? null,
            'name' => $_POST['name'],
            'symbol' => $_POST['symbol']
        ];

        (new Unit())->upsert($data);

        $this->redirect('/unit');
    }

    public function edit($id)
    {
        $isUpdate = true;
        $unitModel = new Unit();
        $unit = $unitModel->findById((int)$id);

        if (!$unit) {
            $this->redirect('/unit');
        }

        include "../views/units/form.php";
    }

    public function delete($id)
    {
        $unitModel = new Unit();
        $unitModel->delete((int)$id);

        $this->json(['success' => true]);
    }

    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name']) && !empty($_POST['symbol'])) {
            $name = trim($_POST['name']);
            $symbol = trim($_POST['symbol']);

            $unit = new Unit();
            $id = $unit->upsert(['unit_id' => null, 'name' => $name, 'symbol' => $symbol]);
            $nova = $unit->findById($id);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'unit' => $nova]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        }
    }
}
