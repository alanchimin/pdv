<?php
namespace App\models;

use PDO;
use App\core\Model;

class Pedido extends Model
{
    public function create($data) {
        $sql = "INSERT INTO pedido (forma_pagamento_id, usuario_id) VALUES (:forma_pagamento_id, :usuario_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }

    public function find($pedido_id) {
        $sql = "
            SELECT p.*, f.nome AS forma_pagamento, u.nome AS usuario
            FROM pedido p
            JOIN forma_pagamento f USING (forma_pagamento_id)
            JOIN usuario u USING (usuario_id)
            WHERE p.pedido_id = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$pedido_id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            return null;
        }

        // Busca os itens do pedido
        $sqlItens = "
            SELECT i.*, pr.nome
            FROM item i
            JOIN produto pr ON pr.produto_id = i.produto_id
            WHERE i.pedido_id = ?
        ";
        $stmtItens = $this->pdo->prepare($sqlItens);
        $stmtItens->execute([$pedido_id]);
        $pedido['itens'] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

        return $pedido;
    }
}
