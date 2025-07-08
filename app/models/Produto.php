<?php
namespace App\models;

use PDO;
use App\core\Model;

class Produto extends Model
{
    public function all(string $busca = '', int $limit = 10, int $offset = 0): array {
        $sql = "
            SELECT p.*, u.nome AS unidade_nome, u.simbolo, c.nome AS categoria_nome
            FROM produto p
            JOIN unidade_medida u ON u.unidade_medida_id = p.unidade_medida_id
            JOIN categoria c ON c.categoria_id = p.categoria_id
            WHERE p.nome LIKE :busca
            ORDER BY p.produto_id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit ?: PHP_INT_MAX, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $busca = ''): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM produto WHERE nome LIKE :busca");
        $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function create(array $data): void {
        $sql = "INSERT INTO produto (nome, imagem, unidade_medida_id, valor_unitario, categoria_id)
                VALUES (:nome, :imagem, :unidade_medida_id, :valor_unitario, :categoria_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
}
