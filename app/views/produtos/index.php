<?php include '../app/views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Cadastro de Produtos</h1>
    <form method="POST" action="index.php?c=produto&a=store">
        <input type="text" name="nome" placeholder="Nome" class="form-control mb-2">
        <input type="number" step="0.01" name="preco" placeholder="Preço" class="form-control mb-2">
        <input type="number" name="estoque" placeholder="Estoque" class="form-control mb-2">
        <button class="btn btn-primary">Salvar</button>
    </form>

    <a href="index.php?c=produto&a=create" class="btn btn-primary mb-3">Novo Produto</a>

    <h2 class="mt-5">Buscar Produtos</h2>
    <input type="text" id="busca" class="form-control mb-2" placeholder="Digite para buscar">
    <ul id="resultados"></ul>

    <h2 class="mt-5">Todos os Produtos</h2>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li><?= htmlspecialchars($produto['nome']) ?> - R$<?= number_format($produto['valor_unitario'], 2, ',', '.') ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include '../app/views/layout/footer.php'; ?>
