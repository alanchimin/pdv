<?php
// views/components/paginacao.php

/**
 * Espera as variáveis:
 * - $paginaAtual (int)
 * - $totalPaginas (int)
 * - $busca (string)
 * - $baseUrl (string) ex: '/produto'
 */

$paginaAtual = $paginaAtual ?? 1;
$totalPaginas = $totalPaginas ?? 1;
$busca = $busca ?? '';
$baseUrl = $baseUrl ?? $_SERVER['PHP_SELF'];

function buildUrl(string $baseUrl, array $params): string {
    return $baseUrl . '?' . http_build_query($params);
}
?>

<nav>
    <ul class="pagination justify-content-center">
        <!-- Primeira página -->
        <li class="page-item <?= $paginaAtual === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= buildUrl($baseUrl, ['q' => $busca, 'pagina' => 1]) ?>">&laquo;</a>
        </li>

        <!-- Página anterior -->
        <li class="page-item <?= $paginaAtual === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= buildUrl($baseUrl, ['q' => $busca, 'pagina' => max(1, $paginaAtual - 1)]) ?>">&lsaquo;</a>
        </li>

        <!-- Páginas centrais -->
        <?php
        $range = 1;
        $inicio = max(1, $paginaAtual - $range);
        $fim = min($totalPaginas, $paginaAtual + $range);

        if ($fim - $inicio < 2) {
            $inicio = max(1, $fim - 2);
            $fim = min($totalPaginas, $inicio + 2);
        }

        for ($i = $inicio; $i <= $fim; $i++): ?>
            <li class="page-item <?= $i === $paginaAtual ? 'active' : '' ?>">
                <a class="page-link" href="<?= buildUrl($baseUrl, ['q' => $busca, 'pagina' => $i]) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Próxima página -->
        <li class="page-item <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= buildUrl($baseUrl, ['q' => $busca, 'pagina' => min($totalPaginas, $paginaAtual + 1)]) ?>">&rsaquo;</a>
        </li>

        <!-- Última página -->
        <li class="page-item <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= buildUrl($baseUrl, ['q' => $busca, 'pagina' => $totalPaginas]) ?>">&raquo;</a>
        </li>
    </ul>
</nav>
