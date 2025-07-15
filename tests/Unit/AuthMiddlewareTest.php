<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\core\AuthMiddleware;
use Tests\Traits\GlobalResetTrait;

/**
 * Dummy que estende AuthMiddleware com modo de teste ativado.
 */
class DummyAuthMiddleware extends AuthMiddleware
{
    public ?string $redirectedTo = null;

    /**
     * Sobrescreve o método redirect para capturar o URL sem executar um redirecionamento real.
     */
    protected function redirect(string $url, int $status = 302): void
    {
        $this->redirectedTo = $url;
        $this->setHeader("Location: $url", true, $status);
        $this->output('[TERMINATED]');
    }
}

/**
 * Testes unitários para o middleware de autenticação.
 */
class AuthMiddlewareTest extends TestCase
{
    use GlobalResetTrait;

    private DummyAuthMiddleware $middleware;

    /**
     * Configura o ambiente antes de cada teste.
     * Cria instância dummy do middleware e limpa variáveis globais relevantes.
     */
    protected function setUp(): void
    {
        $this->middleware = new DummyAuthMiddleware();
        $this->middleware->enableTestMode();

        $_SESSION = [];
        unset($_SERVER['PATH_INFO'], $_SERVER['REQUEST_URI']);
    }

    /**
     * Testa se rotas públicas são permitidas sem autenticação.
     */
    public function testAllowsPublicRouteWithoutAuth()
    {
        $_SERVER['REQUEST_URI'] = '/auth/index';

        $this->middleware->check();

        $this->assertNull($this->middleware->redirectedTo, 'Não deve redirecionar em rota pública');
        $this->assertEmpty($this->middleware->getMockedHeaders(), 'Nenhum header deve ser enviado');
    }

    /**
     * Testa se o middleware redireciona para a página de login se usuário não autenticado tentar acessar rota privada.
     */
    public function testRedirectsToLoginIfNotAuthenticated()
    {
        $_SERVER['REQUEST_URI'] = '/product/index';

        $this->middleware->check();

        $this->assertEquals('/auth', $this->middleware->redirectedTo, 'Deve redirecionar para /auth');
        $this->assertContains('Location: /auth', $this->middleware->getMockedHeaders());
        $this->assertStringContainsString('[TERMINATED]', $this->middleware->getMockedOutput());
    }

    /**
     * Testa que não redireciona se usuário estiver autenticado.
     */
    public function testDoesNotRedirectIfAuthenticated()
    {
        $_SESSION['auth'] = ['user_id' => 1];
        $_SERVER['REQUEST_URI'] = '/product/index';

        $this->middleware->check();

        $this->assertNull($this->middleware->redirectedTo, 'Não deve redirecionar para usuário autenticado');
    }

    /**
     * Testa que não redireciona mesmo sem autenticação ao acessar a página de login.
     */
    public function testDoesNotRedirectOnLoginPageEvenIfNotAuthenticated()
    {
        $_SERVER['REQUEST_URI'] = '/auth/index';

        $this->middleware->check();

        $this->assertNull($this->middleware->redirectedTo, 'Não deve redirecionar na página de login');
    }

    /**
     * Testa que rota padrão ("/") resolve para order/index e redireciona para login se não autenticado.
     */
    public function testDefaultsToOrderIndexIfNoPathGiven()
    {
        $_SERVER['REQUEST_URI'] = '/';

        $this->middleware->check();

        $this->assertEquals('/auth', $this->middleware->redirectedTo, 'Deve redirecionar para /auth na rota padrão');
    }

    /**
     * Testa funcionamento do middleware quando PATH_INFO é usado ao invés de REQUEST_URI.
     */
    public function testWorksWithPathInfoInsteadOfRequestUri()
    {
        $_SERVER['PATH_INFO'] = '/category/index';

        $this->middleware->check();

        $this->assertEquals('/auth', $this->middleware->redirectedTo, 'Deve redirecionar para /auth usando PATH_INFO');
    }
}
