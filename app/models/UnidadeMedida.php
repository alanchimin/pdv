<?php
namespace App\models;

use App\core\Model;
use PDO;

class UnidadeMedida extends Model
{
    public function all(string $orderBy = 'nome') {
        $columns = ['unidade_medida_id', 'nome', 'simbolo'];

        // Verifica se o parâmetro é válido
        if (!in_array($orderBy, $columns)) {
            $orderBy = 'nome';
        }

        $sql = "SELECT * FROM unidade_medida ORDER BY $orderBy";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO unidade_medida (nome, simbolo)
                VALUES (:nome, :simbolo)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
}
