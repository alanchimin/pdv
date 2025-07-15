<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\controllers\CategoryController;
use App\models\Category;
use Tests\Traits\GlobalResetTrait;

class CategoryTest extends TestCase
{
    use GlobalResetTrait;

    private CategoryController $controller;

    /**
     * Cria uma instância do CategoryController com o modelo mockado e modo de teste ativado.
     */
    private function makeControllerWithMock(Category $mock): CategoryController
    {
        return new class($mock) extends CategoryController {
            public function __construct($model)
            {
                parent::__construct($model);
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
        $mock = $this->createMock(Category::class);

        $mock->method('upsert')->willReturn(1);
        $mock->method('delete');
        $mock->method('findById')->willReturn([
            'category_id' => 1,
            'name' => 'Mock Categoria',
            'icon' => 'fa-solid fa-house'
        ]);

        $this->controller = $this->makeControllerWithMock($mock);
    }

    /**
     * Deve renderizar a tela de listagem de categorias.
     */
    public function testIndexShouldRenderCategoryListView()
    {
        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<div id="category-container"', $output);
    }

    /**
     * Deve renderizar o formulário de criação de categoria.
     */
    public function testFormShouldRenderCreateView()
    {
        ob_start();
        $this->controller->form();
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/category/store">', $output);
    }

    /**
     * Deve renderizar o formulário de edição com os dados preenchidos.
     */
    public function testEditShouldRenderEditView()
    {
        ob_start();
        $this->controller->edit(1);
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/category/store">', $output);
        $this->assertStringContainsString('<input type="hidden" name="category_id" value="1">', $output);
    }

    /**
     * Deve redirecionar para /category se a categoria não existir no método edit.
     */
    public function testEditShouldRedirectIfCategoryNotFound()
    {
        $mock = $this->createMock(Category::class);
        $mock->method('findById')->willReturn(null);

        $controller = $this->makeControllerWithMock($mock);
        $controller->edit(999);

        $headers = $controller->getMockedHeaders();
        $this->assertContains('Location: /category', $headers);
    }

    /**
     * Deve criar uma nova categoria e redirecionar para /category.
     */
    public function testCreateShouldInsertNewCategory()
    {
        $_POST = [
            'name' => 'Nova Categoria',
            'icon' => 'fa-solid fa-coffee'
        ];

        $response = $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Location: /category', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Deve retornar erro em JSON quando campos obrigatórios estiverem vazios no método store.
     */
    public function testStoreShouldReturnErrorWhenMissingFields()
    {
        $_POST = ['name' => '', 'icon' => ''];

        $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Nome e ícone são obrigatórios.', $data['message']);
    }

    /**
     * Deve atualizar uma categoria existente e redirecionar para /category.
     */
    public function testUpdateShouldEditExistingCategory()
    {
        $_POST = [
            'category_id' => 1,
            'name' => 'Categoria Editada',
            'icon' => 'fa-solid fa-edit'
        ];

        $response = $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Location: /category', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Deve remover uma categoria e retornar JSON com sucesso.
     */
    public function testDeleteShouldRemoveCategory()
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
     * Deve retornar JSON com sucesso ao criar categoria via AJAX.
     */
    public function testStoreAjaxShouldReturnJsonOnSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'Nova AJAX',
            'icon' => 'fa-solid fa-star'
        ];

        $this->controller->storeAjax();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
        $this->assertEquals('Mock Categoria', $data['category']['name']);
    }

    /**
     * Deve retornar erro JSON quando dados inválidos são enviados via AJAX.
     */
    public function testStoreAjaxShouldReturnErrorWhenDataInvalid()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => '',
            'icon' => ''
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
