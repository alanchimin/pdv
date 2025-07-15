<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Tests\Traits\GlobalResetTrait;

class AuthTest extends TestCase
{
    use GlobalResetTrait;

    /**
     * Cria uma instância do controller de autenticação com modo de teste ativado.
     * Permite capturar headers e saída sem interromper a execução.
     */
    protected function createTestController(): \App\controllers\AuthController
    {
        return new class extends \App\controllers\AuthController {
            public function __construct() {
                $this->enableTestMode();
            }
        };
    }

    /**
     * Testa o comportamento do login com credenciais inválidas.
     * Espera que o usuário seja redirecionado para a página de login com erro.
     */
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

    /**
     * Testa o login com credenciais válidas.
     * Verifica se a sessão é iniciada corretamente e o usuário autenticado está setado.
     */
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
