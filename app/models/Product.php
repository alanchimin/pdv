<?php
namespace App\models;

use PDO;
use App\core\Model;

class Product extends Model
{
    protected string $table = 'products';
    protected string $primaryKey = 'product_id';
    protected array $orderableColumns = [
        'product_id',
        'name',
        'unit_price',
        'symbol',
        'category_name',
        'discount'
    ];

    public function all()
    {
        return $this->list(limit: PHP_INT_MAX, orderBy: 'name', direction: 'asc');
    }

    public function list(string $search = '', int $limit = 10, int $offset = 0, string $orderBy = 'product_id', string $direction = 'desc', ?array $filters = null): array {
        $joins = "
            JOIN units u ON u.unit_id = t.unit_id
            JOIN categories c ON c.category_id = t.category_id
        ";
        $select = "t.*, u.name AS unit_name, u.symbol, c.name AS category_name";

        $whereExtra = '';
        $bindings = [];
        
        if (!empty($filters)) {
            foreach ($filters as $k => $v) {
                $whereExtra .= " AND t.{$k} = :{$k} ";
                $bindings[$k] = $v;
            }
        }

        return $this->baseListQuery(
            search: $search,
            limit: $limit,
            offset: $offset,
            orderBy: $orderBy,
            direction: $direction,
            searchColumn: 'name',
            additionalWhere: $whereExtra,
            bindings: $bindings,
            selectColumns: $select,
            joins: $joins
        );
    }

    public function count(string $search = '', ?array $filters = null): int {
        $whereExtra = '';
        $bindings = [];
        
        if (!empty($filters)) {
            foreach ($filters as $k => $v) {
                $whereExtra .= " AND {$k} = :{$k} ";
                $bindings[$k] = $v;
            }
        }

        return $this->baseCount(
            column: 'name',
            search: $search,
            whereExtra: $whereExtra,
            bindings: $bindings
        );
    }

    public function delete(int $id): void
    {
        // Busca o nome da imagem para excluir da pasta 'upload'
        $stmt = $this->pdo->prepare("SELECT image, image_type FROM products WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) return;

        if ($product['image_type'] === 'upload' && $product['image']) {
            $file = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $product['image'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM products WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findById(int $id): ?array {
        $sql = "
            SELECT p.*, u.name AS unit_name, u.symbol, c.name AS category_name
            FROM products p
            JOIN units u USING (unit_id)
            JOIN categories c USING (category_id)
            WHERE p.product_id = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }
}
