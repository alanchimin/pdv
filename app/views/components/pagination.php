<?php
/**
 * Espera as variáveis:
 * - $currentPage (int)
 * - $totalPages (int)
 */

$currentPage = $currentPage ?? 1;
$totalPages = max(1, $totalPages ?? 1);
?>

<nav class="nav-pagination">
    <ul class="pagination justify-content-center">

        <!-- Primeira página -->
        <li class="page-item <?= $currentPage === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="1">&laquo;</a>
        </li>

        <!-- Página anterior -->
        <li class="page-item <?= $currentPage === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= max(1, $currentPage - 1) ?>">&lsaquo;</a>
        </li>

        <!-- Páginas centrais (máximo 5) -->
        <?php
        $maxPages = 5;
        $half = floor($maxPages / 2);

        $begin = max(1, $currentPage - $half);
        $end = min($totalPages, $begin + $maxPages - 1);

        if (($end - $begin + 1) < $maxPages) {
            $begin = max(1, $end - $maxPages + 1);
        }

        for ($i = $begin; $i <= $end; $i++): ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="#" data-pagina="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Próxima página -->
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= min($totalPages, $currentPage + 1) ?>">&rsaquo;</a>
        </li>

        <!-- Última página -->
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= $totalPages ?>">&raquo;</a>
        </li>
    </ul>
</nav>
