<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\core\ResponseTrait;

/**
 * Classe dummy para expor os métodos do trait para testes.
 */
class DummyResponder {
    use ResponseTrait;

    public function callRespond(string $content = '', string $contentType = 'text/html', ?int $status = null) {
        $this->respond($content, $contentType, $status);
    }

    public function callJson(array $data, int $status = 200) {
        $this->json($data, $status);
    }

    public function callRedirect(string $url, int $status = 302) {
        $this->redirect($url, $status);
    }

    public function callSetHeader(string $header, bool $replace = true, ?int $status = null) {
        $this->setHeader($header, $replace, $status);
    }

    public function callTerminate(?string $msg = null) {
        $this->terminate($msg);
    }

    public function callOutput(string $content) {
        $this->output($content);
    }
}

/**
 * Testes unitários para o trait ResponseTrait.
 */
class ResponseTraitTest extends TestCase
{
    private DummyResponder $responder;

    /**
     * Configura o ambiente para teste ativando o modo teste do trait.
     */
    protected function setUp(): void
    {
        $this->responder = new DummyResponder();
        $this->responder->enableTestMode();
    }

    /** 
     * Testa se o método setHeader armazena corretamente o header no modo teste.
     */
    public function testSetHeaderStoresHeaderInMockedArray()
    {
        $this->responder->callSetHeader('Content-Type: text/plain');
        $headers = $this->responder->getMockedHeaders();

        $this->assertContains('Content-Type: text/plain', $headers);
    }

    /** 
     * Testa se o método output armazena corretamente a saída no modo teste.
     */
    public function testOutputStoresMockedOutput()
    {
        $this->responder->callOutput('Hello World');
        $this->assertEquals('Hello World', $this->responder->getMockedOutput());
    }

    /** 
     * Testa se respond configura headers, saída e finaliza com marcação.
     */
    public function testRespondSetsHeaderAndOutputAndTerminates()
    {
        $this->responder->callRespond('Página completa', 'text/html', 200);

        $headers = $this->responder->getMockedHeaders();
        $output = $this->responder->getMockedOutput();

        $this->assertContains('Content-Type: text/html', $headers);
        $this->assertStringContainsString('Página completa', $output);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /** 
     * Testa o método json para formatar array em JSON e definir header correto.
     */
    public function testJsonResponseFormatsDataAsJson()
    {
        $this->responder->callJson(['success' => true, 'message' => 'OK'], 201);

        $headers = $this->responder->getMockedHeaders();
        $output = $this->responder->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
        $this->assertEquals('OK', $data['message']);
    }

    /**
     * Testa json com dados aninhados para garantir estrutura correta.
     */
    public function testJsonResponseWithNestedData()
    {
        $this->responder->callJson(['data' => ['id' => 1, 'name' => 'Teste']], 200);

        $output = $this->responder->getMockedOutput();
        $this->assertJson($output);
        $data = json_decode($output, true);

        $this->assertEquals(1, $data['data']['id']);
        $this->assertEquals('Teste', $data['data']['name']);
    }

    /**
     * Testa redirecionamento com header Location e finalização.
     */
    public function testRedirectSetsLocationHeaderAndTerminates()
    {
        $this->responder->callRedirect('/login', 302);

        $headers = $this->responder->getMockedHeaders();
        $output = $this->responder->getMockedOutput();

        $this->assertContains('Location: /login', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Testa que terminate adiciona mensagem de finalização customizada no output.
     */
    public function testTerminateAppendsTerminatedMessage()
    {
        $this->responder->callOutput('Finalizando...');
        $this->responder->callTerminate('Mensagem final');

        $output = $this->responder->getMockedOutput();
        $this->assertStringContainsString('Finalizando...', $output);
        $this->assertStringContainsString('[TERMINATED: Mensagem final]', $output);
    }

    /**
     * Testa que terminate sem mensagem adiciona marcação padrão no output.
     */
    public function testTerminateWithoutMessageAppendsDefault()
    {
        $this->responder->callOutput('Antes');
        $this->responder->callTerminate();

        $output = $this->responder->getMockedOutput();
        $this->assertStringContainsString('[TERMINATED]', $output);
    }
}
