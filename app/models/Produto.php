<?php
namespace App\models;

use PDO;
use App\core\Model;

class Produto extends Model
{
    protected string $table = 'produto';
    protected array $orderableColumns = [
        'produto_id',
        'nome',
        'valor_unitario',
        'simbolo',
        'categoria_nome',
        'desconto'
    ];

    public function all()
    {
        return $this->list(limit: PHP_INT_MAX, orderBy: 'nome', direction: 'asc');
    }

    public function list(string $search = '', int $limit = 10, int $offset = 0, string $orderBy = 'produto_id', string $direction = 'desc', ?array $filters = null): array {
        $joins = "
            JOIN unidade_medida u ON u.unidade_medida_id = t.unidade_medida_id
            JOIN categoria c ON c.categoria_id = t.categoria_id
        ";
        $select = "t.*, u.nome AS unidade_nome, u.simbolo, c.nome AS categoria_nome";

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
            searchColumn: 'nome',
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
            column: 'nome',
            search: $search,
            whereExtra: $whereExtra,
            bindings: $bindings
        );
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
