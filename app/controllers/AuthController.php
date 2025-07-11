<?php
namespace App\controllers;

use App\models\Usuario;
use App\models\Tela;

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
        $usuario = $usuarioModel->findByName($user);

        if ($usuario && password_verify($pass, $usuario['senha'])) {
            $_SESSION['auth'] = true;
            $_SESSION['usuario'] = [
                'usuario_id' => $usuario['usuario_id'],
                'nome' => $usuario['nome'],
            ];

            // Busca as telas do usuário
            $telaModel = new Tela();
            $_SESSION['telas'] = $telaModel->getTelasPorUsuario($usuario['usuario_id']);

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
