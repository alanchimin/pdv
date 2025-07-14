<?php
namespace App\core;

use App\core\Model;

trait ListTrait
{
    /**
     * Executes a generic listing for any Model that has count() and list() methods.
     *
     * @param \App\core\Model $model        Model instance
     * @param string          $viewIndex    Main view file (index.php)
     * @param string          $viewTable    Partial view file (table.php)
     * @param string          $entity       Entity/route name (e.g., 'product')
     * @param ?array          $filters      Extra filters to apply in the search
     */
    protected function list(
        Model $model,
        string $viewIndex,
        string $viewTable,
        string $entity,
        ?array $filters = null
    ) {
        // 1) request parameters
        $search      = $_GET['q']        ?? '';
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $limit       = 10;
        $offset      = ($currentPage - 1) * $limit;
        $orderBy     = $_GET['order']    ?? ($entity . '_id');
        $direction   = $_GET['direction'] ?? 'asc';
        $isAjax      = (isset($_GET['ajax']) && $_GET['ajax'] == 1);

        // 2) total and pagination
        $total       = $model->count($search, $filters);
        $totalPages  = (int) ceil($total / $limit);

        // 3) page data
        $items       = $model->list($search, $limit, $offset, $orderBy, $direction);

        // 4) render partial or full
        if ($isAjax) {
            include __DIR__ . "/../views/{$viewTable}";
            exit;
        }

        include __DIR__ . "/../views/{$viewIndex}";
    }
}
