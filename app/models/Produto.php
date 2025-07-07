<?php
namespace App\models;

use PDO;
use App\core\Model;

class Produto extends Model
{
    public function all(): array {
        $stmt = $this->pdo->query("
            SELECT p.*, u.nome AS unidade_nome, u.simbolo, c.nome AS categoria_nome
            FROM produto p
            JOIN unidade_medida u ON u.unidade_medida_id = p.unidade_medida_id
            JOIN categoria c ON c.categoria_id = p.categoria_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO produto (nome, imagem, unidade_medida_id, valor_unitario, categoria_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nome'],
            $data['imagem'],
            $data['unidade_medida_id'],
            $data['valor_unitario'],
            $data['categoria_id']
        ]);
    }

    public function buscar(string $termo): array {
        $stmt = $this->pdo->prepare("SELECT * FROM produto WHERE nome LIKE ?");
        $stmt->execute(['%' . $termo . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
