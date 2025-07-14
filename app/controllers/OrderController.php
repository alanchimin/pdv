<?php
namespace App\controllers;

use Dompdf\Dompdf;

use App\config\Database;
use App\models\Order;
use App\models\Item;
use App\models\Category;
use App\models\Product;
use App\models\PaymentMethod;

class OrderController
{
    public function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $productModel = new Product();
        $products = $productModel->all();

        $paymentMethodModel = new PaymentMethod();
        $paymentMethods = $paymentMethodModel->all();

        include "../views/orders/index.php";
    }

    public function grid()
    {
        $search = $_GET['q'] ?? '';
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($currentPage - 1) * $limit;
        $categoryId = $_GET['category_id'] ?? null;
        $filters = empty($categoryId) ? [] : [ 'category_id' => $categoryId ];

        $productModel = new Product();
        $total = $productModel->count($search, $filters);
        $totalPages = ceil($total / $limit);
        $products = $productModel->list($search, $limit, $offset, 'name', 'asc', $filters);

        include "../views/orders/grid.php";
    }

    public function store() {
        $items = json_decode($_POST['items'] ?? '[]', true);

        if (empty($items)) {
            echo json_encode(['error' => 'Sem itens para finalizar o pedido.']);
            return;
        }

        $pdo = Database::getInstance();

        try {
            $orderModel = new Order();
            $itemModel = new Item();

            // Inicia transação
            $pdo->beginTransaction();

            $orderId = $orderModel->create([
                'payment_method_id' => $_POST['payment_method_id'],
                'user_id' => $_SESSION['user']['user_id']
            ]);

            // Insere os itens do pedido
            foreach ($items as $item) {
                if (!isset($item['amount'], $item['discount'], $item['unitPrice'], $item['productId'])) {
                    throw new \Exception("Dados do item inválidos.");
                }

                $itemModel->create([
                    'amount' => $item['amount'],
                    'discount' => $item['discount'],
                    'unit_price' => $item['unitPrice'],
                    'total_price' => ($item['unitPrice'] * $item['amount']) - $item['discount'],
                    'product_id' => $item['productId'],
                    'order_id' => $orderId
                ]);
            }

            $pdo->commit();

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'order_id' => $orderId]);

        } catch (\Exception $e) {
            $pdo->rollBack();
            echo json_encode(['error' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
            return;
        }
    }

    public function getPdfLink($orderId) {
        $orderModel = new Order();
        $order = $orderModel->find($orderId);

        if (!$order || empty($order['items'])) {
            echo json_encode(['error' => 'Pedido não encontrado.']);
            return;
        }

        // Variáveis para a view
        $items = $order['items'];
        $paymentMethod = $order['payment_method'];
        $user = $order['user'];

        // Gera o HTML da view
        ob_start();
        include '../views/orders/pdf.php';
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
        header('Content-Type: application/json');
        echo json_encode([
            'url' => '/orders/' . $filename
        ]);
    }
}
