<div id="tabela-unidades">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th class="sortable" data-campo="unidade_medida_id">ID</th>
                <th class="sortable" data-campo="nome">Nome</th>
                <th class="sortable" data-campo="simbolo">Símbolo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($unidades as $unidade): ?>
                <tr>
                    <td><?= $unidade['unidade_medida_id'] ?></td>
                    <td><?= htmlspecialchars($unidade['nome']) ?></td>
                    <td><?= htmlspecialchars($unidade['simbolo']) ?></td>
                    <td>
                        <a href="/unidadeMedida/edit/<?= $unidade['unidade_medida_id'] ?>" class="btn btn-sm btn-primary">✏️</a>
                        <a class="btn btn-sm btn-danger btn-excluir" href="#" data-id="<?= $unidade['unidade_medida_id'] ?>">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
        $baseUrl = '/unidadeMedida';
        include __DIR__ . '/../components/pagination.php';
    ?>
</div>
