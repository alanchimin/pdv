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
        <button class="btn btn-dark menu-toggle-btn" id="btn_toggle_menu_mobile">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Overlay para fechar o menu no mobile -->
    <div class="menu-overlay"></div>

    <nav class="sidebar bg-dark text-white vh-100">
        <div class="px-3 pt-3 pb-1 border-bottom border-secondary small">
            <i class="fa-solid fa-user me-1"></i>
            <span class="menu-label"><?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?></span>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-ite align-items-center d-sm-none">
                <a href="#" id="btn_toggle_menu" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-bars"></i><span class="menu-label ms-2">Menu</span>
                </a>
            </li>
            <?php
            $userScreens = $_SESSION['screens'] ?? [];
            $screens = [
                'Pedidos' => ['icon' => 'fa-cash-register', 'route' => '/order'],
                'Produtos' => ['icon' => 'fa-boxes-stacked', 'route' => '/product'],
                'Categorias' => ['icon' => 'fa-tags', 'route' => '/category'],
                'Unidades' => ['icon' => 'fa-ruler-combined', 'route' => '/unit'],
            ];
            ?>

            <?php foreach ($userScreens as $screen): ?>
                <li class="nav-item">
                    <a href="<?= $screens[$screen['name']]['route'] ?>" class="nav-link text-white d-flex align-items-center">
                        <i class="fa-solid <?= $screens[$screen['name']]['icon'] ?>"></i><span class="menu-label ms-2"><?= $screen['name'] ?></span>
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

        <!-- Modal confirmação exclusão -->
        <div class="modal fade" id="modal_confirm_delete" tabindex="-1" aria-labelledby="modal_confirm_delete_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_confirm_delete_label">Confirmar exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">Deseja realmente excluir este registro?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btn_confirm_delete" type="button" class="btn btn-danger">Excluir</button>
                    </div>
                </div>
            </div>
        </div>
<?php endif; ?>
