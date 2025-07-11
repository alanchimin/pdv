<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>PDV</title>
    
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
        <div class="px-3 pt-3 pb-1 border-bottom border-secondary small">
            <i class="fa-solid fa-user me-1"></i>
            <span class="menu-label"><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? '') ?></span>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-ite align-items-center d-sm-none">
                <a href="#" id="btnToggleMenu" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-bars"></i><span class="menu-label ms-2">Menu</span>
                </a>
            </li>
            <?php
            $telasUsuario = $_SESSION['telas'] ?? [];
            $telas = [
                'Pedidos' => ['icone' => 'fa-cash-register', 'rota' => '/pedido'],
                'Produtos' => ['icone' => 'fa-boxes-stacked', 'rota' => '/produto'],
                'Categorias' => ['icone' => 'fa-tags', 'rota' => '/categoria'],
                'Unidades' => ['icone' => 'fa-ruler-combined', 'rota' => '/unidadeMedida'],
            ];
            ?>

            <?php foreach ($telasUsuario as $tela): ?>
                <li class="nav-item">
                    <a href="<?= $telas[$tela['nome']]['rota'] ?>" class="nav-link text-white d-flex align-items-center">
                        <i class="fa-solid <?= $telas[$tela['nome']]['icone'] ?>"></i><span class="menu-label ms-2"><?= $tela['nome'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="nav-item mt-auto">
                <a href="/auth/logout" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-right-from-bracket"></i><span class="menu-label ms-2">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <main class="flex-grow-1 p-0">
        <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080"></div>
<?php endif; ?>
