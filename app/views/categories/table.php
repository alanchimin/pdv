<?php

$entity = 'category';
$primaryKey = 'category_id';
$columns = [
    ['label' => 'ID', 'field' => 'category_id', 'sortable' => true],
    ['label' => 'Categoria', 'field' => 'name', 'sortable' => true],
];
include __DIR__ . '/../components/table.php';
