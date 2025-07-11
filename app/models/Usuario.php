<?php

namespace App\models;

use App\core\Model;
use PDO;

class Usuario extends Model
{
    public function findByName($usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE nome = ?");
        $stmt->execute([$usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
