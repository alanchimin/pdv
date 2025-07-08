<?php
namespace App\core;

class AuthMiddleware
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Captura rota com fallback para REQUEST_URI se PATH_INFO não existir
        $path = $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        $controller = strtolower($segments[0] ?? 'pedido');
        $action     = strtolower($segments[1] ?? 'index');

        $currentRoute = "$controller/$action";
        $authenticated = isset($_SESSION['auth']);

        $publicRoutes = [
            'auth/index',
            'auth/login',
        ];

        $isPublicRoute = in_array($currentRoute, $publicRoutes);

        if (!$authenticated && !$isPublicRoute) {
            // Evita redirecionamento infinito ao garantir que já não está na tela de login
            if ($currentRoute !== 'auth/index') {
                header("Location: /auth");
                exit;
            }
        }
    }
}
