<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\controllers\ProductController;
use App\models\Product;
use App\models\Unit;
use App\models\Category;
use Tests\Traits\GlobalResetTrait;

class ProductTest extends TestCase
{
    use GlobalResetTrait;

    private ProductController $controller;

    /**
     * Cria o controller com models mockados e modo de teste ativado.
     */
    private function makeControllerWithMocks(Product $productMock, Unit $unitMock, Category $categoryMock): ProductController
    {
        return new class($productMock, $unitMock, $categoryMock) extends ProductController {
            public function __construct($product, $unit, $category)
            {
                parent::__construct($product, $unit, $category);
                $this->enableTestMode();
            }
        };
    }

    /**
     * Configura mocks para cada teste.
     */
    protected function setUp(): void
    {
        $productMock = $this->createMock(Product::class);
        $unitMock = $this->createMock(Unit::class);
        $categoryMock = $this->createMock(Category::class);

        // Mock métodos usados em ProductController
        $productMock->method('all')->willReturn([
            ['product_id' => 1, 'name' => 'Mock Produto']
        ]);
        $productMock->method('findById')->willReturn([
            'product_id' => 1,
            'name' => 'Mock Produto',
            'image' => null,
            'image_type' => 'url',
            'unit_id' => 1,
            'unit_price' => 9.99,
            'discount' => null,
            'category_id' => 1
        ]);
        $productMock->method('upsert')->willReturn(1);
        $productMock->method('delete');

        $unitMock->method('all')->willReturn([
            ['unit_id' => 1, 'name' => 'Unidade', 'symbol' => 'Un']
        ]);

        $categoryMock->method('all')->willReturn([
            ['category_id' => 1, 'name' => 'Categoria']
        ]);

        $this->controller = $this->makeControllerWithMocks($productMock, $unitMock, $categoryMock);
    }

    /**
     * Deve renderizar a tela de listagem de produtos.
     */
    public function testIndexShouldRenderProductListView()
    {
        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('<div id="product-container"', $output);
    }

    /**
     * Deve renderizar o formulário de criação de produto.
     */
    public function testFormShouldRenderCreateView()
    {
        ob_start();
        $this->controller->form();
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/product/store" enctype="multipart/form-data">', $output);
    }

    /**
     * Deve renderizar o formulário de edição com os dados preenchidos.
     */
    public function testEditShouldRenderEditView()
    {
        ob_start();
        $this->controller->edit(1);
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/product/store" enctype="multipart/form-data">', $output);
        $this->assertStringContainsString('<input type="hidden" name="product_id" value="1">', $output);
    }

    /**
     * Deve redirecionar para /product se o produto não existir no método edit.
     */
    public function testEditShouldRedirectIfProductNotFound()
    {
        $productMock = $this->createMock(Product::class);
        $productMock->method('findById')->willReturn(null);

        $unitMock = $this->createMock(Unit::class);
        $categoryMock = $this->createMock(Category::class);

        $controller = $this->makeControllerWithMocks($productMock, $unitMock, $categoryMock);
        $controller->edit(999);

        $headers = $controller->getMockedHeaders();
        $this->assertContains('Location: /product', $headers);
    }

    /**
     * Deve criar um novo produto e redirecionar para /product.
     */
    public function testCreateShouldInsertNewProduct()
    {
        $_POST = [
            'name' => 'Produto Teste',
            'image_type' => 'url',
            'image_url' => 'http://example.com/image.jpg',
            'unit_id' => 1,
            'unit_price' => 10.5,
            'discount' => '',
            'category_id' => 1,
        ];

        $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Location: /product', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Deve retornar erro em JSON quando campos obrigatórios estiverem vazios no método store.
     */
    public function testStoreShouldReturnErrorWhenMissingFields()
    {
        $_POST = [
            'name' => '',
            'category_id' => '',
            'unit_id' => '',
            'unit_price' => '',
            'discount' => '',
            'image_type' => 'url',
            'image_url' => ''
        ];

        $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertFalse($data['success']);
        $this->assertEquals('Dados inválidos.', $data['message']);
    }

    /**
     * Deve atualizar um produto existente e redirecionar para /product.
     */
    public function testUpdateShouldEditExistingProduct()
    {
        $_POST = [
            'product_id' => 1,
            'name' => 'Produto Editado',
            'image_type' => 'url',
            'image_url' => 'http://example.com/edited_image.jpg',
            'unit_id' => 1,
            'unit_price' => 15.00,
            'discount' => 5,
            'category_id' => 1,
        ];

        $this->controller->store();

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Location: /product', $headers);
        $this->assertStringContainsString('[TERMINATED]', $output);
    }

    /**
     * Deve remover um produto e retornar JSON com sucesso.
     */
    public function testDeleteShouldRemoveProduct()
    {
        $this->controller->delete(1);

        $headers = $this->controller->getMockedHeaders();
        $output = $this->controller->getMockedOutput();

        $this->assertContains('Content-Type: application/json', $headers);
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
    }
}
