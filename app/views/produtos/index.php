<?php include '../views/layout/header.php'; ?>

<div id="produto-container">
    <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
        <div class="flex-grow-1">
            <h2 class="mb-2">Produtos</h2>
            <form method="GET" action="/produto" class="d-flex">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar produto...">
            </form>
        </div>
        <div>
            <a href="/produto/create" class="btn btn-success">Novo Produto</a>
        </div>
    </div>

    <?php include 'tabela.php'; ?>
</div>


<?php include '../views/layout/footer.php'; ?>

<script src="/js/produtos/index.js"></script>
