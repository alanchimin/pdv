<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\core\Router;
use Tests\Traits\GlobalResetTrait;

/**
 * Testes unitários para a classe Router.
 * Verifica comportamento da resolução de rotas, validação de nomes e respostas para casos inválidos.
 */
class RouterTest extends TestCase
{
    use GlobalResetTrait;

    private Router $router;

    /**
     * Inicializa uma instância do Router antes de cada teste.
     * Observação: Caso Router use ResponseTrait, pode ser necessário mockar métodos para evitar saída real.
     */
    protected function setUp(): void
    {
        $this->router = new Router();
    }

    /**
     * Testa que o Router responde com erro 400 (Bad Request)
     * quando o nome do controller ou da action não é válido.
     */
    public function testHandleRequestInvalidController()
    {
        $_SERVER['PATH_INFO'] = '/invalid-controller!/@action';

        $router = new class extends Router {
            public string $responseContent = '';
            public int $responseStatus = 0;

            protected function respond(string $content = '', string $contentType = 'text/html', ?int $status = null): void
            {
                $this->responseContent = $content;
                $this->responseStatus = $status ?? 0;
            }
        };

        $router->handleRequest();

        $this->assertEquals('Requisição inválida.', $router->responseContent);
        $this->assertEquals(400, $router->responseStatus);
    }

    /**
     * Testa que o Router responde com erro 404 (Not Found)
     * quando a classe do controller não existe.
     */
    public function testHandleRequestNotFound()
    {
        $_SERVER['PATH_INFO'] = '/nonexistentcontroller/index';

        $router = new class extends Router {
            public string $responseContent = '';
            public int $responseStatus = 0;

            protected function respond(string $content = '', string $contentType = 'text/html', ?int $status = null): void
            {
                $this->responseContent = $content;
                $this->responseStatus = $status ?? 0;
            }
        };

        $router->handleRequest();

        $this->assertEquals('Página não encontrada.', $router->responseContent);
        $this->assertEquals(404, $router->responseStatus);
    }

    /**
     * Testa que o Router localiza um controller e método válidos,
     * invocando o método com os parâmetros da URL corretamente.
     */
    public function testHandleRequestValidRouteCallsControllerAction()
    {
        $_SERVER['PATH_INFO'] = '/dummy/testaction/param1/param2';

        // Cria dinamicamente o controller com método testaction
        eval('
            namespace App\controllers;
            class DummyController {
                public static string $called = "";
                public function testaction($p1, $p2) {
                    self::$called = "called with $p1 and $p2";
                }
            }
        ');

        $router = new class extends \App\core\Router {
            protected function respond(string $content = "", string $contentType = "text/html", ?int $status = null): void {}
        };

        \App\controllers\DummyController::$called = '';

        $router->handleRequest();

        $this->assertEquals('called with param1 and param2', \App\controllers\DummyController::$called);
    }

    /**
     * Testa que o Router responde com erro 404 (Not Found)
     * quando o método especificado na URL não existe no controller.
     * Garante que o controller é válido, mas o método não é encontrado.
     */
    public function testHandleRequestMethodNotFound()
    {
        $_SERVER['PATH_INFO'] = '/dummy/invalidmethod';

        if (!class_exists(\App\controllers\DummyController::class)) {
            eval('
                namespace App\controllers;
                class DummyController {
                }
            ');
        }

        $router = new class extends \App\core\Router {
            public string $responseContent = '';
            public int $responseStatus = 0;
            protected function respond(string $content = "", string $contentType = "text/html", ?int $status = null): void {
                $this->responseContent = $content;
                $this->responseStatus = $status ?? 0;
            }
        };

        $router->handleRequest();

        $this->assertEquals('Página não encontrada.', $router->responseContent);
        $this->assertEquals(404, $router->responseStatus);
    }
}
