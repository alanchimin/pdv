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
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }
}
