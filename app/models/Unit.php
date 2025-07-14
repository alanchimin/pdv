<?php
namespace App\models;

use App\core\Model;
use PDO;

class Unit extends Model
{
    protected string $table = 'units';
    protected string $primaryKey = 'unit_id';
    protected array $orderableColumns = [
        'unit_id',
        'name',
        'symbol',
    ];

    public function all()
    {
        return $this->list(limit: PHP_INT_MAX, orderBy: 'name', direction: 'asc');
    }

    public function list(string $search = '', int $limit = 10, int $offset = 0, string $orderBy = 'name', string $direction = 'asc'): array {
        return $this->baseListQuery(
            search: $search,
            limit: $limit,
            offset: $offset,
            orderBy: $orderBy,
            direction: $direction,
            searchColumn: 'name'
        );
    }

    public function count(string $search = '', ?array $filters = null): int {
        return $this->baseCount('name', $search);
    }
}
