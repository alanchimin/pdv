<?php
namespace App\models;

use PDO;
use App\core\Model;

class Item extends Model
{
    public function allByComanda($comanda_id) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, p.nome AS produto_nome
            FROM item i
            JOIN produto p ON p.produto_id = i.produto_id
            WHERE i.comanda_id = ?
        ");
        $stmt->execute([$comanda_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO item (
                quantidade, desconto_porcentagem, desconto_valor, valor_unitario,
                valor_total, produto_id, comanda_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['quantidade'],
            $data['desconto_porcentagem'] ?? 0,
            $data['desconto_valor'] ?? 0,
            $data['valor_unitario'],
            $data['valor_total'],
            $data['produto_id'],
            $data['comanda_id']
        ]);
    }
}
