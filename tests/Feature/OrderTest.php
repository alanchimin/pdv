<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\controllers\OrderController;
use App\models\Category;
use App\models\Product;
use App\models\PaymentMethod;
use App\models\Order;
use App\models\Item;
use PDO;
use Tests\Traits\GlobalResetTrait;

class OrderTest extends TestCase
{
    use GlobalResetTrait;

    private OrderController $controller;

    private $categoryMock;
    private $productMock;
    private $paymentMethodMock;
    private $orderMock;
    private $itemMock;
    private $pdoMock;

    protected function setUp(): void
    {
        // Cria mocks para todos os models e PDO
        $this->categoryMock = $this->createMock(Category::class);
        $this->productMock = $this->createMock(Product::class);
        $this->paymentMethodMock = $this->createMock(PaymentMethod::class);
        $this->orderMock = $this->createMock(Order::class);
        $this->itemMock = $this->createMock(Item::class);
        $this->pdoMock = $this->createMock(PDO::class);

        // Cria controller anônimo para habilitar o modo de teste do ResponseTrait
        $this->controller = new class(
            $this->categoryMock,
            $this->productMock,
            $this->paymentMethodMock,
            $this->orderMock,
            $this->itemMock,
            $this->pdoMock
        ) extends OrderController {
            public function __construct(...$args)
            {
                parent::__construct(...$args);
                $this->enableTestMode();
            }
        };
    }

    /**
     * Testa se a view principal de pedidos está sendo renderizada corretamente
     */
    public function testIndexShouldIncludeView()
    {
        $this->categoryMock->method('all')->willReturn([['category_id' => 1, 'name' => 'Cat 1']]);
        $this->productMock->method('all')->willReturn([['product_id' => 1, 'name' => 'Prod 1']]);
        $this->paymentMethodMock->method('all')->willReturn([['payment_method_id' => 1, 'name' => 'Dinheiro']]);

        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Carrinho', $output);
    }

    /**
     * Testa se a grid de produtos está sendo renderizada corretamente com AJAX
     */
    public function testGridShouldRenderProductsGrid()
    {
        $this->productMock->method('count')->willReturn(1);
        $this->productMock->method('list')->willReturn([
            [
                'product_id' => 1,
                'name' => 'Product 1',
                'unit_price' => 9.99,
                'category_id' => 2,
                'symbol' => 'un',
                'discount' => 0.00
            ]
        ]);

        $_GET['q'] = '';
        $_GET['page'] = '1';

        ob_start();
        $this->controller->grid();
        $output = ob_get_clean();

        $this->assertStringContainsString('data-id="1"', $output);
    }

    /**
     * Testa a validação de tentativa de salvar um pedido sem itens
     */
    public function testStoreShouldReturnErrorIfNoItems()
    {
        $_POST['items'] = json_encode([]);

        $this->controller->store();

        $output = $this->controller->getMockedOutput();
        $data = json_decode($output, true);

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Sem itens para finalizar o pedido.', $data['error']);
    }

    /**
     * Testa o fluxo de criação de pedido e seus itens com sucesso
     */
    public function testStoreShouldSaveOrderAndItemsSuccessfully()
    {
        $_POST['items'] = json_encode([
            ['amount' => 2, 'discount' => 1, 'unitPrice' => 10, 'productId' => 5]
        ]);
        $_POST['payment_method_id'] = 1;
        $_SESSION['user']['user_id'] = 123;

        $this->pdoMock->expects($this->once())->method('beginTransaction');
        $this->pdoMock->expects($this->once())->method('commit');
        $this->pdoMock->expects($this->never())->method('rollBack');

        $this->orderMock->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('payment_method_id'))
            ->willReturn(42);

        $this->itemMock->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($itemData) {
                return $itemData['order_id'] === 42
                    && $itemData['amount'] === 2
                    && $itemData['discount'] === 1
                    && $itemData['unit_price'] === 10
                    && $itemData['product_id'] === 5;
            }));

        $this->controller->store();

        $output = $this->controller->getMockedOutput();
        $data = json_decode($output, true);

        $this->assertTrue($data['success']);
        $this->assertEquals(42, $data['order_id']);
    }

    /**
     * Testa se uma exceção ao salvar os itens causa rollback da transação
     */
    public function testStoreShouldRollBackOnException()
    {
        $_POST['items'] = json_encode([
            ['amount' => 2, 'discount' => 1, 'unitPrice' => 10]
        ]);
        $_POST['payment_method_id'] = 1;
        $_SESSION['user']['user_id'] = 123;

        $this->pdoMock->expects($this->once())->method('beginTransaction');
        $this->pdoMock->expects($this->never())->method('commit');
        $this->pdoMock->expects($this->once())->method('rollBack');

        $this->orderMock->method('create')->willReturn(42);

        $this->controller->store();

        $output = $this->controller->getMockedOutput();
        $data = json_decode($output, true);

        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('Dados do item inválidos', $data['error']);
    }

    /**
     * Testa o caso em que o pedido não é encontrado ao gerar link do PDF
     */
    public function testGetPdfLinkShouldReturnErrorIfOrderNotFound()
    {
        $this->orderMock->method('find')->willReturn(null);

        $this->controller->getPdfLink(999);

        $output = $this->controller->getMockedOutput();
        $data = json_decode($output, true);

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Pedido não encontrado.', $data['error']);
    }

    /**
     * Testa a geração do link para download do PDF do pedido
     */
    public function testGetPdfLinkShouldGeneratePdfAndReturnUrl()
    {
        $orderData = [
            'items' => [
                [
                    'name' => 'Item 1',
                    'unit_price' => 10.00,
                    'amount' => 2,
                    'discount' => 1.00,
                ],
            ],
            'payment_method' => 'Dinheiro',
            'user' => 'Usuário Teste'
        ];
        $this->orderMock->method('find')->willReturn($orderData);

        $this->controller->getPdfLink(1);

        $output = $this->controller->getMockedOutput();
        $data = json_decode($output, true);

        $this->assertArrayHasKey('url', $data);
        $this->assertStringContainsString('/orders/order_1_', $data['url']);
    }
}
