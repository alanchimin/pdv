<?php
namespace App\models;

use PDO;
use App\core\Model;

class Order extends Model
{
    public function create($data) {
        $sql = "INSERT INTO orders (payment_method_id, user_id) VALUES (:payment_method_id, :user_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function find($orderId) {
        $sql = "
            SELECT o.*, pm.name AS payment_method, u.name AS user
            FROM orders o
            JOIN payment_methods pm USING (payment_method_id)
            JOIN users u USING (user_id)
            WHERE o.order_id = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return null;
        }

        // Busca os itens do pedido
        $sqlItems = "
            SELECT i.*, p.name
            FROM items i
            JOIN products p USING (product_id)
            WHERE i.order_id = ?
        ";
        $stmtItems = $this->pdo->prepare($sqlItems);
        $stmtItems->execute([$orderId]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }
}
