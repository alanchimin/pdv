<?php
namespace App\models;

use App\core\Model;
use PDO;

class Category extends Model
{
    protected string $table = 'categories';
    protected string $primaryKey = 'category_id';
    protected array $orderableColumns = [
        'category_id',
        'name'
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
