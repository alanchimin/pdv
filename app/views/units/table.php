<?php

$entity = 'unit';
$primaryKey = 'unit_id';
$columns = [
    [ 'label' => 'ID', 'field' => 'unit_id', 'sortable' => true],
    [ 'label' => 'Nome', 'field' => 'name', 'sortable' => true],
    [ 'label' => 'Símbolo', 'field' => 'symbol', 'sortable' => true],
];
include __DIR__ . '/../components/table.php';
