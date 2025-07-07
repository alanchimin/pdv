<?php
namespace App\models;

use PDO;
use App\core\Model;

class FormaPagamento extends Model
{
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM forma_pagamento");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
