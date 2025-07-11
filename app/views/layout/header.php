<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>PDV Supermercado</title>
    
    <!-- Font Awesome -->
    <link href="/assets/fontawesome/css/all.min.css" rel="stylesheet" />
    
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/lib/bootstrap/bootstrap-select.min.css">
    <link rel="stylesheet" href="/css/lib/bootstrap/bootstrap-icons.min.css">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="/css/core/core.css">
    <link rel="stylesheet" href="/css/core/menu.css">
</head>
<body>

<?php if (!str_contains($_SERVER['REQUEST_URI'], '/auth')): ?>
<div class="d-flex">
    <!-- Botão Flutuante Menu Mobile -->
    <div class="d-flex align-items-center d-sm-none p-2">
        <button class="btn btn-dark menu-toggle-btn" id="btnToggleMenuMobile">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Overlay para fechar o menu no mobile -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <nav class="sidebar bg-dark text-white vh-100">
        <ul class="nav flex-column mt-3">
            <li class="nav-ite align-items-center">
                <a href="#" id="btnToggleMenu" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-bars"></i><span class="menu-label ms-2">Menu</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/pedido" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-cash-register"></i><span class="menu-label ms-2">Pedidos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/produto" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-boxes-stacked"></i><span class="menu-label ms-2">Produtos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/categoria" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-tags"></i><span class="menu-label ms-2">Categorias</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/unidadeMedida" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-ruler-combined"></i><span class="menu-label ms-2">Unidades</span>
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a href="/auth/logout" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-right-from-bracket"></i><span class="menu-label ms-2">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <main class="flex-grow-1 p-0">
<?php endif; ?>
