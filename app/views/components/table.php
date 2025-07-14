<div id="table-<?= $entity ?>">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <th<?= $col['sortable'] ? ' class="sortable" data-field="' . $col['field'] . '"' : '' ?>>
                            <?= $col['label'] ?>
                        </th>
                    <?php endforeach; ?>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <td><?= htmlspecialchars($item[$col['field']] ?? '') ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="/<?= $entity ?>/edit/<?= $item[$primaryKey] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $item[$primaryKey] ?>" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
        $baseUrl = '/' . $entity;
        include __DIR__ . '/pagination.php';
    ?>
</div>

<script src="/js/core/list.js"></script>
