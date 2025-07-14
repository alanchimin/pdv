<?php
$entity = 'product';
$primaryKey = 'product_id';
$columns = [
    ['label' => 'ID', 'field' => 'product_id', 'sortable' => true],
    ['label' => 'Nome', 'field' => 'name', 'sortable' => true],
    ['label' => 'Categoria', 'field' => 'category_name', 'sortable' => true],
    ['label' => 'Un. Medida', 'field' => 'symbol', 'sortable' => true],
    ['label' => 'Valor Unitário (R$)', 'field' => 'unit_price', 'sortable' => true],
    ['label' => 'Desconto (%)', 'field' => 'discount', 'sortable' => true],
];
include __DIR__ . '/../components/table.php';
