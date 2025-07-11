<?php
namespace App\models;

use App\core\Model;
use PDO;

class Categoria extends Model
{
    protected string $table = 'categoria';
    protected array $orderableColumns = [
        'categoria_id',
        'nome'
    ];

    public function all()
    {
        return $this->list(limit: PHP_INT_MAX, orderBy: 'nome', direction: 'asc');
    }

    public function list(string $search = '', int $limit = 10, int $offset = 0, string $orderBy = 'nome', string $direction = 'asc'): array {
        return $this->baseListQuery(
            search: $search,
            limit: $limit,
            offset: $offset,
            orderBy: $orderBy,
            direction: $direction,
            searchColumn: 'nome'
        );
    }

    public function count(string $search = '', ?array $filters = null): int {
        return $this->baseCount('nome', $search);
    }
}
