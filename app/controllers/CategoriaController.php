<?php
namespace App\controllers;

use App\models\Categoria;

class CategoriaController {
    public function index() {
        $categorias = Categoria::all();
        include "../views/categorias/index.php";
    }

    public function create() {
        include "../views/categorias/create.php";
    }

    public function store() {
        Categoria::create($_POST);
        header("Location: index.php?c=categoria");
    }
}
