<?php
namespace App\models;

use App\core\Model;
use PDO;

class Categoria extends Model
{
    public function all(string $orderBy = 'nome') {
        $columns = ['categoria_id', 'nome'];

        // Verifica se o parâmetro é válido
        if (!in_array($orderBy, $columns)) {
            $orderBy = 'nome';
        }

        $sql = "SELECT * FROM categoria ORDER BY $orderBy";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO categoria (nome) VALUES (:nome)");
        $stmt->execute($data);
    }
}
