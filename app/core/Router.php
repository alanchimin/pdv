<?php
namespace App\core;

use App\core\ResponseTrait;

class Router
{
    use ResponseTrait;

    public function handleRequest() {
        $path = $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove barras iniciais/finais e divide a URL
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        $controller = $segments[0] ?? 'order';
        $action     = $segments[1] ?? 'index';
        $params     = array_slice($segments, 2); // tudo após /controller/action/

        // Segurança
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $controller) || !preg_match('/^[a-zA-Z0-9_]+$/', $action)) {
            $this->respond('Requisição inválida.', 'text/plain', 400);
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

        $this->respond('Página não encontrada.', 'text/plain', 404);
    }
}
