<?php
namespace App\core;

class AuthMiddleware
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $controller = $_GET['c'] ?? '';
        $action = $_GET['a'] ?? '';

        $rotaPublica = $controller === 'auth' && in_array($action, ['login', 'doLogin']);

        if (!isset($_SESSION['auth']) && !$rotaPublica) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }
}
