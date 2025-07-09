<?php
namespace App\models;

use PDO;
use App\core\Model;

class Produto extends Model
{
    public function all(string $busca = '', int $limit = 10, int $offset = 0, string $ordem = 'produto_id', string $direcao = 'desc'): array {
        $colunasPermitidas = ['produto_id', 'nome', 'valor_unitario', 'simbolo', 'categoria_nome'];
        $direcao = strtolower($direcao) === 'asc' ? 'ASC' : 'DESC';
        $ordem = in_array($ordem, $colunasPermitidas) ? $ordem : 'produto_id';

        $sql = "
            SELECT p.*, u.nome AS unidade_nome, u.simbolo, c.nome AS categoria_nome
            FROM produto p
            JOIN unidade_medida u ON u.unidade_medida_id = p.unidade_medida_id
            JOIN categoria c ON c.categoria_id = p.categoria_id
            WHERE p.nome LIKE :busca
            ORDER BY $ordem $direcao
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
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
        $sql = "INSERT INTO produto (nome, imagem, tipo_imagem, unidade_medida_id, valor_unitario, categoria_id)
                VALUES (:nome, :imagem, :tipo_imagem, :unidade_medida_id, :valor_unitario, :categoria_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        // Busca o nome da imagem para excluir da pasta 'upload'
        $stmt = $this->pdo->prepare("SELECT imagem, tipo_imagem FROM produto WHERE produto_id = :id");
        $stmt->execute(['id' => $id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) return;

        if ($produto['tipo_imagem'] === 'upload' && $produto['imagem']) {
            $file = __DIR__ . '/../public/upload/' . $produto['imagem'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM produto WHERE produto_id = :id");
        $stmt->execute(['id' => $id]);
    }

}
