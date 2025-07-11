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
            <a class="page-link" href="#" data-pagina="1" aria-label="Primeira página">&laquo;</a>
        </li>

        <!-- Página anterior -->
        <li class="page-item <?= $currentPage === 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= max(1, $currentPage - 1) ?>" aria-label="Página anterior">&lsaquo;</a>
        </li>

        <!-- Página atual no col-xs -->
        <li class="page-item active d-sm-none">
            <a class="page-link" href="#" aria-label="Página atual"><?= $currentPage ?></a>
        </li>

        <!-- Páginas centrais (máximo 5) (exibido apenas em col-sm ou maior) -->
        <span class="d-none d-sm-flex">
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
                    <a class="page-link" href="#" data-pagina="<?= $i ?>" aria-label="Página <?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </span>

        <!-- Próxima página -->
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= min($totalPages, $currentPage + 1) ?>" aria-label="Próxima página">&rsaquo;</a>
        </li>

        <!-- Última página -->
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="#" data-pagina="<?= $totalPages ?>" aria-label="Última página">&raquo;</a>
        </li>
    </ul>
</nav>
