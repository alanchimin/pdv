<?php

$entidade = 'categoria';
$chavePrimaria = 'categoria_id';
$colunas = [
    ['label' => 'ID', 'campo' => 'categoria_id', 'sortable' => true],
    ['label' => 'Categoria', 'campo' => 'nome', 'sortable' => true],
];
include __DIR__ . '/../components/table.php';
