<?php
namespace App\controllers;

use App\core\ResponseTrait;
use App\models\User;
use App\models\Screen;

class AuthController
{
    use ResponseTrait;

    public function index()
    {
        $this->startSession();
        include __DIR__ . '/../views/auth/index.php';
    }

    public function login()
    {
        $this->startSession();

        $user = trim($_POST['user'] ?? '');
        $pass = trim($_POST['pass'] ?? '');

        if (empty($user) || empty($pass)) {
            $this->redirect('/auth?error=1');
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

            $this->redirect('/order');
        }

        $this->redirect('/auth?error=1');
    }

    public function logout()
    {
        $this->startSession();
        $_SESSION = [];
        session_destroy();
        $this->redirect('/auth');
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
