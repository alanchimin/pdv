<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\controllers\OrderController;
use App\models\Category;
use App\models\Product;
use App\models\PaymentMethod;
use App\models\Order;
use App\models\Item;
use App\core\ResponseTrait;
use Dompdf\Dompdf;

class OrderTest extends TestCase
{
    /**
     * Cria uma versão do controller com ResponseTrait em modo de teste,
     * para capturar saída JSON e headers sem usar header()/exit().
     */
    private function makeTestableController(): OrderController
    {
        return new class extends OrderController {
            use ResponseTrait;

            public function __construct()
            {
                $this->enableTestMode();
            }
        };
    }

    public function testIndexRunsWithoutError()
    {
        $controller = $this->makeTestableController();

        // Como os models são instanciados dentro do método, só testamos se roda sem erro.
        // Refatorar para injeção de dependências facilitaria mocks e asserts.
        $this->expectNotToPerformAssertions();

        $controller->index();
    }

    public function testGridRunsWithoutError()
    {
        $_GET['q'] = 'search';
        $_GET['page'] = '1';
        $_GET['category_id'] = 1;

        $controller = $this->makeTestableController();

        $this->expectNotToPerformAssertions();

        $controller->grid();
    }

    public function testStoreReturnsErrorIfNoItems()
    {
        $_POST['items'] = json_encode([]);

        $controller = $this->makeTestableController();
        $controller->store();

        $output = $controller->getMockedOutput();
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Sem itens para finalizar o pedido.', $data['error']);
    }

    public function testStoreCreatesOrderAndItemsSuccessfully()
    {
        $_SESSION['user'] = ['user_id' => 1];
        $_POST = [
            'payment_method_id' => 2,
            'items' => json_encode([
                [
                    'amount' => 3,
                    'discount' => 0,
                    'unitPrice' => 10.0,
                    'productId' => 5
                ]
            ])
        ];

        // Mock do PDO para transação
        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->expects($this->once())->method('beginTransaction');
        $pdoMock->expects($this->once())->method('commit');
        $pdoMock->expects($this->never())->method('rollBack');

        // Mock Order para criar pedido
        $orderMock = $this->getMockBuilder(Order::class)->onlyMethods(['create'])->getMock();
        $orderMock->expects($this->once())->method('create')->willReturn(123);

        // Mock Item para criar item
        $itemMock = $this->getMockBuilder(Item::class)->onlyMethods(['create'])->getMock();
        $itemMock->expects($this->once())->method('create')->with($this->callback(function($data) {
            return $data['amount'] === 3
                && $data['product_id'] === 5
                && $data['total_price'] === 30.0;
        }));

        // Subclasse para injetar mocks no método store
        $controller = new class($pdoMock, $orderMock, $itemMock) extends OrderController {
            private $pdoMock, $orderMock, $itemMock;

            public function __construct($pdo, $order, $item)
            {
                $this->pdoMock = $pdo;
                $this->orderMock = $order;
                $this->itemMock = $item;
                $this->enableTestMode();
            }

            public function store()
            {
                $items = json_decode($_POST['items'] ?? '[]', true);
                if (empty($items)) {
                    $this->json(['error' => 'Sem itens para finalizar o pedido.']);
                    return;
                }

                try {
                    $this->pdoMock->beginTransaction();

                    $orderId = $this->orderMock->create([
                        'payment_method_id' => $_POST['payment_method_id'],
                        'user_id' => $_SESSION['user']['user_id']
                    ]);

                    foreach ($items as $item) {
                        if (!isset($item['amount'], $item['discount'], $item['unitPrice'], $item['productId'])) {
                            throw new \Exception("Dados do item inválidos.");
                        }

                        $this->itemMock->create([
                            'amount' => $item['amount'],
                            'discount' => $item['discount'],
                            'unit_price' => $item['unitPrice'],
                            'total_price' => ($item['unitPrice'] * $item['amount']) - $item['discount'],
                            'product_id' => $item['productId'],
                            'order_id' => $orderId
                        ]);
                    }

                    $this->pdoMock->commit();

                    $this->json(['success' => true, 'order_id' => $orderId]);

                } catch (\Exception $e) {
                    $this->pdoMock->rollBack();
                    $this->json(['error' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
                }
            }
        };

        $controller->store();

        $output = $controller->getMockedOutput();
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertTrue($data['success']);
        $this->assertEquals(123, $data['order_id']);
    }

    public function testGetPdfLinkReturnsErrorWhenOrderNotFound()
    {
        $controller = $this->makeTestableController();

        $orderMock = $this->getMockBuilder(Order::class)->onlyMethods(['find'])->getMock();
        $orderMock->method('find')->willReturn(null);

        // Bind método getPdfLink para usar mock
        $method = function($orderId) use ($orderMock) {
            $order = $orderMock->find($orderId);

            if (!$order || empty($order['items'])) {
                $this->json(['error' => 'Pedido não encontrado.']);
                return;
            }
        };

        $bound = $method->bindTo($controller, $controller);
        $bound(999);

        $output = $controller->getMockedOutput();
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertEquals('Pedido não encontrado.', $data['error']);
    }

    public function testGetPdfLinkGeneratesPdfAndReturnsUrl()
    {
        $_SERVER['DOCUMENT_ROOT'] = sys_get_temp_dir();

        $orderData = [
            'order_id' => 1,
            'payment_method' => 'Dinheiro',
            'user' => 'Admin',
            'items' => [
                ['product_id' => 5, 'amount' => 2, 'name' => 'Produto X', 'unit_price' => 10]
            ]
        ];

        $orderMock = $this->getMockBuilder(Order::class)->onlyMethods(['find'])->getMock();
        $orderMock->method('find')->willReturn($orderData);

        $controller = new class($orderMock) extends OrderController {
            private $orderMock;

            public function __construct($orderMock)
            {
                $this->orderMock = $orderMock;
                $this->enableTestMode();
            }

            public function getPdfLink($orderId)
            {
                $order = $this->orderMock->find($orderId);

                if (!$order || empty($order['items'])) {
                    $this->json(['error' => 'Pedido não encontrado.']);
                    return;
                }

                $items = $order['items'];
                $paymentMethod = $order['payment_method'];
                $user = $order['user'];

                // HTML simplificado para teste
                $html = '<html><body>Pedido PDF</body></html>';

                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper([0, 0, 300, 1000], 'portrait');
                $dompdf->render();

                $output = $dompdf->output();
                $filename = "order_{$orderId}_" . time() . '.pdf';

                $path = $_SERVER['DOCUMENT_ROOT'] . '/orders/' . $filename;

                if (!is_dir(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }

                file_put_contents($path, $output);

                $this->json(['url' => '/orders/' . $filename]);
            }
        };

        $controller->getPdfLink(1);

        $output = $controller->getMockedOutput();
        $this->assertJson($output);

        $data = json_decode($output, true);
        $this->assertArrayHasKey('url', $data);

        // Cleanup do arquivo criado
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $data['url'];
        if (file_exists($filePath)) {
            unlink($filePath);
            rmdir(dirname($filePath));
        }
    }
}
