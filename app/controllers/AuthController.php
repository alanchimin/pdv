<?php
namespace App\controllers;

use App\models\User;
use App\models\Screen;

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

        $userModel = new User();
        $user = $userModel->findByName($user);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['auth'] = true;
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'name' => $user['name'],
            ];

            // Busca as telas do usuário
            $screenModel = new Screen();
            $_SESSION['screens'] = $screenModel->getScreensByUser($user['user_id']);

            header("Location: /order");
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
