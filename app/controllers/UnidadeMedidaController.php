<?php
namespace App\controllers;

use App\models\UnidadeMedida;

class UnidadeMedidaController {
    public function index() {
        $unidades = UnidadeMedida::all();
        include "../app/views/unidades/index.php";
    }

    public function create() {
        include "../app/views/unidades/create.php";
    }

    public function store() {
        UnidadeMedida::create($_POST);
        header("Location: index.php?c=unidademedida");
    }
}
