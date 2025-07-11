<div id="tabela-unidades">
    <div class="table-responsive">
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
                            <a href="/unidadeMedida/edit/<?= $unidade['unidade_medida_id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-excluir" data-id="<?= $unidade['unidade_medida_id'] ?>" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
        $baseUrl = '/unidadeMedida';
        include __DIR__ . '/../components/pagination.php';
    ?>
</div>
