<?php
namespace App\models;

use App\core\Model;
use PDO;

class UnidadeMedida extends Model
{
    public function all()
    {
        return $this->list(limit: PHP_INT_MAX, orderBy: 'nome', direction: 'asc');
    }

    public function list(string $search = '', int $limit = 10, int $offset = 0, string $orderBy = 'unidade_medida_id', string $direction = 'desc'): array {
        $columns = ['unidade_medida_id', 'nome', 'simbolo'];
        $orderBy = in_array($orderBy, $columns) ? $orderBy : 'nome';
        $direction = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM unidade_medida WHERE nome LIKE :search ORDER BY $orderBy $direction LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $busca = ''): int {
        $sql = "SELECT COUNT(*) FROM unidade_medida WHERE nome LIKE :busca";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function upsert(array $data): int {
        $sql = "
            INSERT INTO unidade_medida (unidade_medida_id, nome, simbolo)
            VALUES (:unidade_medida_id, :nome, :simbolo)
            ON DUPLICATE KEY UPDATE
                nome = VALUES(nome),
                simbolo = VALUES(simbolo)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $data['unidade_medida_id'] ?? $this->pdo->lastInsertId();
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM unidade_medida WHERE unidade_medida_id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM unidade_medida WHERE unidade_medida_id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
