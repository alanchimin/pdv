<?php
namespace App\core;

use App\core\Model;

trait ListagemTrait
{
    /**
     * Executa listagem genérica de qualquer Model que tenha métodos count() e list().
     *
     * @param \App\core\Model $model      Instância do model
     * @param string           $viewIndex Arquivo de view principal (index.php)
     * @param string           $viewTable Arquivo de view parcial (table.php)
     * @param string           $entidade  Nome da rota/entidade (ex: 'produto')
     * @param ?array           $filters   Filtros extra para aplicar na busca
     */
    protected function listar(
        Model $model,
        string $viewIndex,
        string $viewTable,
        string $entidade,
        ?array $filters = null
    ) {
        // 1) parâmetros de requisição
        $search      = $_GET['q']       ?? '';
        $currentPage = max(1, (int)($_GET['pagina'] ?? 1));
        $limit       = 10;
        $offset      = ($currentPage - 1) * $limit;
        $orderBy     = $_GET['ordem']   ?? ($entidade . '_id');
        $direction   = $_GET['direcao'] ?? 'asc';
        $isAjax      = (isset($_GET['ajax']) && $_GET['ajax'] == 1);

        // 2) total e páginas
        $total       = $model->count($search, $filters);
        $totalPages  = (int) ceil($total / $limit);

        // 3) dados da página
        $itens       = $model->list($search, $limit, $offset, $orderBy, $direction);

        // 4) renderiza parcial ou full
        if ($isAjax) {
            include __DIR__ . "/../views/{$viewTable}";
            exit;
        }

        include __DIR__ . "/../views/{$viewIndex}";
    }
}
