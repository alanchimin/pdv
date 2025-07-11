<div id="tabela-<?= $entidade ?>">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <?php foreach ($colunas as $col): ?>
                        <th<?= $col['sortable'] ? ' class="sortable" data-campo="' . $col['campo'] . '"' : '' ?>>
                            <?= $col['label'] ?>
                        </th>
                    <?php endforeach; ?>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <?php foreach ($colunas as $col): ?>
                            <td><?= htmlspecialchars($item[$col['campo']] ?? '') ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="/<?= $entidade ?>/edit/<?= $item[$chavePrimaria] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-excluir" data-id="<?= $item[$chavePrimaria] ?>" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
        $baseUrl = '/' . $entidade;
        include __DIR__ . '/pagination.php';
    ?>
</div>

<script src="/js/core/list.js"></script>
