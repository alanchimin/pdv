<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\core\AuthMiddleware;
use App\core\Router;

// Habilita suporte a caracteres especiais corretamente
header('Content-Type: text/html; charset=utf-8');

AuthMiddleware::check();

$router = new Router();
$router->handleRequest();
