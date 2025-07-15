<?php
namespace Tests\Traits;

/**
 * Trait para resetar variáveis globais comuns após cada teste.
 * Evita efeitos colaterais entre testes que manipulam superglobais.
 */
trait GlobalResetTrait
{
    /**
     * Método executado após cada teste.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $_POST = [];
        $_GET = [];
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        unset($_SERVER['PATH_INFO']);

        // Limpa os arquivos temporários criados para o teste
        $viewDir = $_ENV['VIEWS_PATH'] ?? '';
        if (is_dir($viewDir)) {
            array_map('unlink', glob("$viewDir/*/*.php"));
        }

        unset($_ENV['VIEWS_PATH']);
    }
}
