<?php
$entidade = 'produto';
$chavePrimaria = 'produto_id';
$colunas = [
    ['label' => 'ID', 'campo' => 'produto_id', 'sortable' => true],
    ['label' => 'Nome', 'campo' => 'nome', 'sortable' => true],
    ['label' => 'Categoria', 'campo' => 'categoria_nome', 'sortable' => true],
    ['label' => 'Un. Medida', 'campo' => 'simbolo', 'sortable' => true],
    ['label' => 'Valor Unitário (R$)', 'campo' => 'valor_unitario', 'sortable' => true],
    ['label' => 'Desconto (%)', 'campo' => 'desconto', 'sortable' => true],
];
include __DIR__ . '/../components/table.php';
