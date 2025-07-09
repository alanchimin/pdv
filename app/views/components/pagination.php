<?php
/**
 * Espera as variáveis:
 * - $paginaAtual (int)
 * - $totalPaginas (int)
 */

$paginaAtual = $paginaAtual ?? 1;
$totalPaginas = $totalPaginas ?? 1;
?>

<nav class="nav-pagination">
    <ul class="pagination justify-content-center">

        <!-- Primeira página -->
        <li class="page-item <?= $paginaAtual === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="1">&laquo;</a>
        </li>

        <!-- Página anterior -->
        <li class="page-item <?= $paginaAtual === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= max(1, $paginaAtual - 1) ?>">&lsaquo;</a>
        </li>

        <!-- Páginas centrais (máximo 5) -->
        <?php
        $maxPaginas = 5;
        $metade = floor($maxPaginas / 2);

        $inicio = max(1, $paginaAtual - $metade);
        $fim = min($totalPaginas, $inicio + $maxPaginas - 1);

        if (($fim - $inicio + 1) < $maxPaginas) {
            $inicio = max(1, $fim - $maxPaginas + 1);
        }

        for ($i = $inicio; $i <= $fim; $i++): ?>
            <li class="page-item <?= $i === $paginaAtual ? 'active' : '' ?>">
                <a class="page-link" href="#" data-pagina="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Próxima página -->
        <li class="page-item <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= min($totalPaginas, $paginaAtual + 1) ?>">&rsaquo;</a>
        </li>

        <!-- Última página -->
        <li class="page-item <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= $totalPaginas ?>">&raquo;</a>
        </li>
    </ul>
</nav>
