<?php
namespace App\models;

use PDO;
use App\core\Model;

class Item extends Model
{
    public function allByOrder($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, p.name AS product_name
            FROM items i
            JOIN products p USING (product_id)
            WHERE i.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $sql = "INSERT INTO items (amount, discount, unit_price, total_price, product_id, order_id)
                VALUES (:amount, :discount, :unit_price, :total_price, :product_id, :order_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
}
