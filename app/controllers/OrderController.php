<?php
namespace App\controllers;

use Dompdf\Dompdf;

use App\config\Database;
use App\core\ResponseTrait;
use App\models\Order;
use App\models\Item;
use App\models\Category;
use App\models\Product;
use App\models\PaymentMethod;
use PDO;

class OrderController
{
    use ResponseTrait;

    protected Category $categoryModel;
    protected Product $productModel;
    protected PaymentMethod $paymentMethodModel;
    protected Order $orderModel;
    protected Item $itemModel;
    protected PDO $pdo;

    public function __construct(
        ?Category $category = null,
        ?Product $product = null,
        ?PaymentMethod $paymentMethod = null,
        ?Order $order = null,
        ?Item $item = null,
        ?PDO $pdo = null
    ) {
        $this->categoryModel = $category ?? new Category();
        $this->productModel = $product ?? new Product();
        $this->paymentMethodModel = $paymentMethod ?? new PaymentMethod();
        $this->orderModel = $order ?? new Order();
        $this->itemModel = $item ?? new Item();
        $this->pdo = $pdo ?? Database::getInstance();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->all();
        $products = $this->productModel->all();
        $paymentMethods = $this->paymentMethodModel->all();

        include __DIR__ . '/../views/orders/index.php';
    }

    public function grid(): void
    {
        $search = $_GET['q'] ?? '';
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($currentPage - 1) * $limit;
        $categoryId = $_GET['category_id'] ?? null;
        $filters = empty($categoryId) ? [] : ['category_id' => $categoryId];

        $total = $this->productModel->count($search, $filters);
        $totalPages = ceil($total / $limit);
        $products = $this->productModel->list($search, $limit, $offset, 'name', 'asc', $filters);

        include __DIR__ . '/../views/orders/grid.php';
    }

    public function store(): void
    {
        $items = json_decode($_POST['items'] ?? '[]', true);

        if (empty($items)) {
            $this->json(['error' => 'Sem itens para finalizar o pedido.']);
            return;
        }

        try {
            $this->pdo->beginTransaction();

            $orderId = $this->orderModel->create([
                'payment_method_id' => $_POST['payment_method_id'],
                'user_id' => $_SESSION['user']['user_id']
            ]);

            foreach ($items as $item) {
                if (!isset($item['amount'], $item['discount'], $item['unitPrice'], $item['productId'])) {
                    throw new \Exception("Dados do item inválidos.");
                }

                $this->itemModel->create([
                    'amount' => $item['amount'],
                    'discount' => $item['discount'],
                    'unit_price' => $item['unitPrice'],
                    'total_price' => ($item['unitPrice'] * $item['amount']) - $item['discount'],
                    'product_id' => $item['productId'],
                    'order_id' => $orderId
                ]);
            }

            $this->pdo->commit();

            $this->json(['success' => true, 'order_id' => $orderId]);

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->json(['error' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
        }
    }

    public function getPdfLink(int $orderId): void
    {
        $order = $this->orderModel->find($orderId);

        if (!$order || empty($order['items'])) {
            $this->json(['error' => 'Pedido não encontrado.']);
            return;
        }

        // Variáveis para a view
        $items = $order['items'];
        $paymentMethod = $order['payment_method'];
        $user = $order['user'];

        ob_start();
        include __DIR__ . '/../views/orders/pdf.php';
        $html = ob_get_clean();

        // Gera o PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 300, 1000], 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $filename = "order_{$orderId}_" . time() . '.pdf';

        $path = $_SERVER['DOCUMENT_ROOT'] . '/orders/' . $filename;

        // Garante que o diretório existe
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Salva o arquivo PDF
        file_put_contents($path, $output);

        // Retorna a URL para o frontend
        $this->json(['url' => '/orders/' . $filename]);
    }
}
