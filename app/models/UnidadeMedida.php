<?php
namespace App\models;

use App\core\Model;
use PDO;

class UnidadeMedida extends Model
{
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM unidade_medida");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO unidade_medida (nome, simbolo) VALUES (?, ?)");
        $stmt->execute([$data['nome'], $data['simbolo']]);
    }
}
