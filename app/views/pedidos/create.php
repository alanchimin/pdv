<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Novo Pedido</h1>

    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger">Por favor, preencha todos os campos corretamente.</div>
    <?php endif; ?>

    <form method="POST" action="index.php?c=pedido&a=store">
        <div class="mb-3">
            <label>Quantidade Total:</label>
            <input type="number" name="quantidade_total" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label>Valor Total (R$):</label>
            <input type="number" name="valor_total" step="0.01" class="form-control" min="0.01" required>
        </div>

        <div class="mb-3">
            <label>Forma de Pagamento:</label>
            <select name="forma_pagamento_id" class="form-control" required>
                <option value="">Selecione...</option>
                <?php foreach ($formasPagamento as $fp): ?>
                    <option value="<?= $fp['forma_pagamento_id'] ?>"><?= htmlspecialchars($fp['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-success">Salvar Pedido</button>
        <a href="index.php?c=pedido" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../views/layout/footer.php'; ?>
