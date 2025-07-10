<?php
namespace App\models;

use PDO;
use App\core\Model;

class Produto extends Model
{
    public function all()
    {
        return $this->list(limit: PHP_INT_MAX, orderBy: 'nome', direction: 'asc');
    }

    public function list(string $search = '', int $limit = 10, int $offset = 0, string $orderBy = 'produto_id', string $direction = 'desc', int $categoryId = 0): array {
        $columns = ['produto_id', 'nome', 'valor_unitario', 'simbolo', 'categoria_nome', 'desconto'];
        $orderBy = in_array($orderBy, $columns) ? $orderBy : 'produto_id';
        $direction = strtolower($direction) === 'asc' ? 'ASC' : 'DESC';
        $filterCategory = $categoryId ? " AND p.categoria_id = :categoria_id " : "";

        $sql = "
            SELECT p.*, u.nome AS unidade_nome, u.simbolo, c.nome AS categoria_nome
            FROM produto p
            JOIN unidade_medida u ON u.unidade_medida_id = p.unidade_medida_id
            JOIN categoria c ON c.categoria_id = p.categoria_id
            WHERE p.nome LIKE :search $filterCategory
            ORDER BY $orderBy $direction
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if ($categoryId) $stmt->bindValue(':categoria_id', $categoryId, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $busca = '', int $categoriaId = 0): int {
        $filterCategory = $categoriaId ? " AND categoria_id = :categoria_id " : "";

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM produto WHERE nome $filterCategory LIKE :busca");
        $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
        if ($categoriaId) $stmt->bindValue(':categoria_id', $categoriaId, PDO::PARAM_INT);

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function upsert(array $data): int {
        $sql = "
            INSERT INTO produto (produto_id, nome, imagem, tipo_imagem, unidade_medida_id, valor_unitario, categoria_id, desconto)
            VALUES (:produto_id, :nome, :imagem, :tipo_imagem, :unidade_medida_id, :valor_unitario, :categoria_id, :desconto)
            ON DUPLICATE KEY UPDATE
                nome = VALUES(nome),
                imagem = VALUES(imagem),
                tipo_imagem = VALUES(tipo_imagem),
                unidade_medida_id = VALUES(unidade_medida_id),
                valor_unitario = VALUES(valor_unitario),
                categoria_id = VALUES(categoria_id),
                desconto = VALUES(desconto)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $data['produto_id'] ?? $this->pdo->lastInsertId();
    }

    public function delete(int $id): void
    {
        // Busca o nome da imagem para excluir da pasta 'upload'
        $stmt = $this->pdo->prepare("SELECT imagem, tipo_imagem FROM produto WHERE produto_id = :id");
        $stmt->execute(['id' => $id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) return;

        if ($produto['tipo_imagem'] === 'upload' && $produto['imagem']) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $produto['imagem'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM produto WHERE produto_id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findById(int $id): ?array {
        $sql = "
            SELECT p.*, u.nome AS unidade_nome, u.simbolo, c.nome AS categoria_nome
            FROM produto p
            JOIN unidade_medida u ON u.unidade_medida_id = p.unidade_medida_id
            JOIN categoria c ON c.categoria_id = p.categoria_id
            WHERE p.produto_id = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $produto ?: null;
    }
}
