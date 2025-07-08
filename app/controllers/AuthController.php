<?php
namespace App\controllers;

use App\models\Usuario;

class AuthController
{
    public function index()
    {
        $this->startSession();
        include "../views/auth/index.php";
    }

    public function login()
    {
        $this->startSession();

        $user = trim($_POST['user'] ?? '');
        $pass = trim($_POST['pass'] ?? '');

        if (empty($user) || empty($pass)) {
            $this->redirectToLoginWithError();
            return;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByUsuario($user);

        if ($usuario && password_verify($pass, $usuario['senha'])) {
            $_SESSION['auth'] = true;
            $_SESSION['usuario'] = $usuario['usuario'];
            header("Location: /pedido");
            exit;
        }

        $this->redirectToLoginWithError();
    }

    public function logout()
    {
        $this->startSession();
        session_destroy();
        header("Location: /auth");
        exit;
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function redirectToLoginWithError()
    {
        header("Location: /auth?error=1");
        exit;
    }
}
