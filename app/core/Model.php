<?php
namespace App\core;

use App\config\Database;
use PDO;

abstract class Model {
    protected PDO $pdo;
    protected string $table;
    protected array $orderableColumns = [];

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    protected function baseListQuery(
        string $search = '',
        int $limit = 10,
        int $offset = 0,
        string $orderBy = 'id',
        string $direction = 'desc',
        string $searchColumn = 'nome',
        string $additionalWhere = '',
        array $bindings = [],
        string $selectColumns = '*',
        string $joins = ''
    ): array {
        $orderBy = in_array($orderBy, $this->orderableColumns) ? $orderBy : $this->orderableColumns[0] ?? 'id';
        $direction = strtolower($direction) === 'asc' ? 'ASC' : 'DESC';

        $sql = "
            SELECT $selectColumns
            FROM {$this->table} t
            $joins
            WHERE t.$searchColumn LIKE :search
            $additionalWhere
            ORDER BY $orderBy $direction
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function baseCount(string $column, string $search = '', string $whereExtra = '', array $bindings = []): int {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE $column LIKE :search $whereExtra";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);

        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->table}_id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->table}_id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function upsert(array $data): int {
        $keys = array_keys($data);
        $columns = implode(', ', $keys);
        $placeholders = implode(', ', array_map(fn($k) => ":$k", $keys));
        $updates = implode(', ', array_map(fn($k) => "$k = VALUES($k)", $keys));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)
                ON DUPLICATE KEY UPDATE $updates";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        $pk = "{$this->table}_id";
        return $data[$pk] ?? $this->pdo->lastInsertId();
    }
}
