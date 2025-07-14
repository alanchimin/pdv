<?php
namespace App\models;

use PDO;
use App\core\Model;

class PaymentMethod extends Model
{
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM payment_methods ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
