<?php
namespace App\core;

class Router {
    public function handleRequest() {

        $controller = $_GET['c'] ?? 'vendas';
        $action = $_GET['a'] ?? 'index';

        // Segurança: permite apenas letras
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $controller) || !preg_match('/^[a-zA-Z0-9_]+$/', $action)) {
            http_response_code(400);
            echo "Requisição inválida.";
            return;
        }

        $controllerClass = "\\App\\Controllers\\" . ucfirst($controller) . "Controller";

        if (class_exists($controllerClass)) {
            $obj = new $controllerClass();
            if (method_exists($obj, $action)) {
                $obj->$action();
                return;
            }
        }

        http_response_code(404);
        echo "Página não encontrada";
    }
}
