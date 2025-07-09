<div id="tabela-categorias">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th class="sortable" data-campo="categoria_id">ID</th>
                <th class="sortable" data-campo="nome">Nome da Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= $categoria['categoria_id'] ?></td>
                    <td><?= htmlspecialchars($categoria['nome']) ?></td>
                    <td>
                        <a href="/categoria/edit/<?= $categoria['categoria_id'] ?>" class="btn btn-sm btn-primary">✏️</a>
                        <a class="btn btn-sm btn-danger btn-excluir" href="#" data-id="<?= $categoria['categoria_id'] ?>">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
        $baseUrl = '/categoria';
        include __DIR__ . '/../components/pagination.php';
    ?>
</div>
