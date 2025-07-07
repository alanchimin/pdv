<?php
namespace App\models;

use App\core\Model;
use PDO;

class Categoria extends Model
{
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM categoria");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO categoria (nome) VALUES (?)");
        $stmt->execute([$data['nome']]);
    }
}
