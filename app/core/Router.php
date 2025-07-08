<?php
namespace App\core;

class Router {
    public function handleRequest() {
        $path = $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove barras iniciais/finais e divide a URL
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        $controller = $segments[0] ?? 'pedido';
        $action     = $segments[1] ?? 'index';
        $params     = array_slice($segments, 2); // tudo após /controller/action/

        // Segurança
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $controller) || !preg_match('/^[a-zA-Z0-9_]+$/', $action)) {
            http_response_code(400);
            echo "Requisição inválida.";
            return;
        }

        $controllerClass = "\\App\\controllers\\" . ucfirst($controller) . "Controller";

        if (class_exists($controllerClass)) {
            $obj = new $controllerClass();

            if (method_exists($obj, $action)) {
                // Chama com os parâmetros extras, se existirem
                call_user_func_array([$obj, $action], $params);
                return;
            }
        }

        http_response_code(404);
        echo "Página não encontrada.";
    }
}
