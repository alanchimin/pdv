<div id="tabela-produtos">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th class="sortable" data-campo="produto_id">ID</th>
                <th class="sortable" data-campo="nome">Nome do Produto</th>
                <th class="sortable" data-campo="categoria_nome">Categoria</th>
                <th class="sortable" data-campo="simbolo">Un. Medida</th>
                <th class="sortable" data-campo="valor_unitario">Valor Unitário</th>
                <th class="sortable" data-campo="desconto">Desconto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto['produto_id'] ?></td>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= htmlspecialchars($produto['categoria_nome']) ?></td>
                    <td><?= htmlspecialchars($produto['simbolo']) ?></td>
                    <td>R$ <?= number_format($produto['valor_unitario'], 2, ',', '.') ?></td>
                    <td>
                        <?php if ($produto['tipo_desconto'] == 'reais'): ?>R$<?php endif; ?>
                        <?= number_format($produto['desconto'] ?? 0, 2, ',', '.') ?>
                        <?php if ($produto['tipo_desconto'] == 'percentual'): ?>%<?php endif; ?>
                    </td>
                    <td>
                        <a href="/produto/edit/<?= $produto['produto_id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-excluir" data-id="<?= $produto['produto_id'] ?>" title="Excluir">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
        $baseUrl = '/produto';
        include __DIR__ . '/../components/pagination.php';
    ?>
</div>
