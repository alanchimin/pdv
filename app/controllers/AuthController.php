<?php
namespace App\controllers;

use App\models\Usuario;

class AuthController
{
    public function login()
    {
        $this->startSession();
        include "../app/views/auth/login.php";
    }

    public function doLogin()
    {
        $this->startSession();

        $user = trim($_POST['user'] ?? '');
        $pass = trim($_POST['pass'] ?? '');

        if (empty($user) || empty($pass)) {
            $this->redirectToLoginWithError();
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByUsuario($user);

        if ($usuario && password_verify($pass, $usuario['senha'])) {
            $_SESSION['auth'] = true;
            $_SESSION['usuario'] = $usuario['usuario']; // opcional
            header("Location: index.php");
            exit;
        }

        $this->redirectToLoginWithError();
    }

    public function logout()
    {
        $this->startSession();
        session_destroy();
        header("Location: index.php?c=auth&a=login");
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
        header("Location: index.php?c=auth&a=login&error=1");
        exit;
    }
}
