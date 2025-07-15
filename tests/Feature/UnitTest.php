<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\controllers\UnitController;
use App\models\Unit;
use Tests\Traits\GlobalResetTrait;

class UnitTest extends TestCase
{
    use GlobalResetTrait;

    private UnitController $controller;

    /**
     * Cria uma instância do UnitController com o modelo mockado e modo de teste ativado.
     */
    private function makeControllerWithMock(Unit $mock): UnitController
    {
        return new class($mock) extends UnitController {
            public function __construct($mock)
            {
                $this->model = $mock;
                $this->enableTestMode();
            }
        };
    }

    /**
     * Configuração inicial antes de cada teste.
     * Cria o mock do model e instancia o controller em modo de teste.
     */
    protected function setUp(): void
    {
        $mock = $this->createMock(Unit::class);

        $mock->method('upsert')->willReturn(1);
        $mock->method('delete');
        $mock->method('findById')->willReturn([
            'unit_id' => 1,
            'name' => 'Litro',
            'symbol' => 'L'
        ]);

        $this->controller = $this->makeControllerWithMock($mock);
    }

    /**
     * Deve renderizar a tela de listagem de unidades de medida.
     */
    public function testIndexShouldRenderUnitListView()
    {
        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<div id="unit-container"', $output);
    }

    /**
     * Deve renderizar o formulário de criação de unidade de medida.
     */
    public function testFormShouldRenderCreateView()
    {
        ob_start();
        $this->controller->form();
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/unit/store">', $output);
    }

    /**
     * Deve renderizar o formulário de edição com os dados preenchidos.
     */
    public function testEditShouldRenderEditView()
    {
        ob_start();
        $this->controller->edit(1);
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/unit/store">', $output);
        $this->assertStringContainsString('<input type="hidden" name="unit_id" value="1">', $output);
    }

    /**
     * Deve redirecionar para /unit se a unidade de medida não existir no método edit.
     */
    public function testEditShouldRedirectIfUnitNotFound()
    {
        $mock = $this->createMock(Unit::class);
        $mock->method('findById')->willReturn(null);

        $controller = $this->makeControllerWithMock($mock);
        $controller->edit(999);

        $headers = $controller->getMockedHeaders();
        $this->assertContains('Location: /unit', $headers);
    }

    /**
     * Deve criar uma nova unidade d emedida e redirecionar para /unit.
     */
    public function testCreateShouldInsertNewUnit()
    {
        $_POST = [
            'name' => 'Nova Un. Medida',
            'symbol' => 'num'
        ];

        $response = $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Location: /unit', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Deve retornar erro em JSON quando campos obrigatórios estiverem vazios no método store.
     */
    public function testStoreShouldReturnErrorWhenMissingFields()
    {
        $_POST = ['name' => '', 'symbol' => ''];

        $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Nome e símbolo são obrigatórios.', $data['message']);
    }

    /**
     * Deve atualizar uma unidade de medida existente e redirecionar para /unit.
     */
    public function testUpdateShouldEditExistingUnit()
    {
        $_POST = [
            'unit_id' => 1,
            'name' => 'Un. Medida Editada',
            'symbol' => 'umedt'
        ];

        $response = $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Location: /unit', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Deve remover uma unidade de medida e retornar JSON com sucesso.
     */
    public function testDeleteShouldRemoveUnit()
    {
        $response = $this->controller->delete(1);

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
    }

    /**
     * Deve retornar JSON com sucesso ao criar unidade de medida via AJAX.
     */
    public function testStoreAjaxShouldReturnJsonOnSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'Nova Un. Medida',
            'symbol' => 'num'
        ];

        $this->controller->storeAjax();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Litro', $data['unit']['name']);
    }

    /**
     * Deve retornar erro JSON quando dados inválidos são enviados via AJAX.
     */
    public function testStoreAjaxShouldReturnErrorWhenDataInvalid()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => '',
            'symbol' => ''
        ];

        $this->controller->storeAjax();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Dados inválidos', $data['message']);
    }

    /**
     * Deve retornar erro JSON quando storeAjax for chamado com método diferente de POST.
     */
    public function testStoreAjaxShouldFailIfNotPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET'; // Simula requisição incorreta

        $this->controller->storeAjax();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertFalse($data['success']);
    }
}
