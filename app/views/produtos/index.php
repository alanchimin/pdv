<?php include '../views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="/produto" class="d-flex w-50">
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar produto...">
        <button class="btn btn-outline-secondary ms-2">Buscar</button>
    </form>
    <a href="/produto/create" class="btn btn-success">Novo Produto</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nome do Produto</th>
            <th>Categoria</th>
            <th>Un. Medida</th>
            <th>Valor Unitário</th>
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
                    <a href="/produto/edit/<?= $produto['produto_id'] ?>" class="btn btn-sm btn-primary">✏️</a>
                    <a href="/produto/delete/<?= $produto['produto_id'] ?>" class="btn btn-sm btn-danger">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
    $baseUrl = '/produto';
    include __DIR__ . '/../components/paginacao.php';
?>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/common/form-handlers.js"></script>
<script src="/js/produtos/index.js"></script>
