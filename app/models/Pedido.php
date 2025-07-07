<?php
namespace App\models;

use PDO;
use App\core\Model;

class Pedido extends Model
{
    public function all() {
        $stmt = $this->pdo->query("
            SELECT p.*, f.nome AS forma_pagamento
            FROM pedido p
            JOIN forma_pagamento f ON f.forma_pagamento_id = p.forma_pagamento_id
            ORDER BY p.data_hora DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO pedido (data_hora, quantidade_total, valor_total, forma_pagamento_id)
            VALUES (NOW(), ?, ?, ?)
        ");
        $stmt->execute([
            $data['quantidade_total'],
            $data['valor_total'],
            $data['forma_pagamento_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function getFormasPagamento() {
        $stmt = $this->pdo->query("SELECT * FROM forma_pagamento ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
