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
                        <a href="/categoria/edit/<?= $categoria['categoria_id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-excluir" data-id="<?= $categoria['categoria_id'] ?>" title="Excluir">
                            <i class="bi bi-trash"></i>
                        </button>
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
