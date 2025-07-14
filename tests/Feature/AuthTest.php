<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected function createTestController(): \App\controllers\AuthController
    {
        return new class extends \App\controllers\AuthController {
            public function __construct() {
                $this->enableTestMode();
            }
        };
    }

    public function testLoginWithInvalidCredentials()
    {
        $_POST['user'] = 'invalid';
        $_POST['pass'] = 'wrong';

        ob_start();
        $controller = $this->createTestController();
        $controller->login();
        $output = ob_get_clean();

        $headers = $controller->getMockedHeaders();

        $this->assertTrue(
            in_array('Location: /auth?error=1', $headers),
            'Esperado redirecionamento para /auth?error=1'
        );
    }

    public function testLoginWithValidCredentials()
    {
        $_POST['user'] = 'admin';
        $_POST['pass'] = '1';

        $_SESSION = [];

        ob_start();
        $controller = $this->createTestController();
        $controller->login();
        ob_end_clean();

        $this->assertTrue(isset($_SESSION['auth']) && $_SESSION['auth'] === true);
        $this->assertEquals('admin', $_SESSION['user']['name']);
    }
}
