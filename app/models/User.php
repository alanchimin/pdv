<?php

namespace App\models;

use App\core\Model;
use PDO;

class User extends Model
{
    public function findByName($name) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
