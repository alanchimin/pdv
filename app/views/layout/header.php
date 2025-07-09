<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>PDV Supermercado</title>
    
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/bootstrap-select.min.css">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php if (!str_contains($_SERVER['REQUEST_URI'], '/auth')): ?>
<div class="d-flex">
    <nav class="bg-dark text-white p-3 vh-100" style="min-width: 220px;">
        <h5>PDV Menu</h5>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="/pedido" class="nav-link text-white">Pedidos</a></li>
            <li class="nav-item"><a href="/produto" class="nav-link text-white">Produtos</a></li>
            <li class="nav-item"><a href="/categoria" class="nav-link text-white">Categorias</a></li>
            <li class="nav-item"><a href="/unidadeMedida" class="nav-link text-white">Unidades de Medida</a></li>
        </ul>
    </nav>
    <main class="flex-grow-1 p-0">
<?php endif; ?>
