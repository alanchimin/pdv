<?php

$entidade = 'unidadeMedida';
$chavePrimaria = 'unidade_medida_id';
$colunas = [
    [ 'label' => 'ID', 'campo' => 'unidade_medida_id', 'sortable' => true],
    [ 'label' => 'Nome', 'campo' => 'nome', 'sortable' => true],
    [ 'label' => 'Símbolo', 'campo' => 'simbolo', 'sortable' => true],
];
include __DIR__ . '/../components/table.php';
