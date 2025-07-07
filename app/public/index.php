<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\core\AuthMiddleware;
use App\core\Router;

AuthMiddleware::check();

$router = new Router();
$router->handleRequest();
