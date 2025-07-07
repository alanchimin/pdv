<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Pedidos</h1>
    <a href="index.php?c=pedido&a=create" class="btn btn-primary mb-3">Novo Pedido</a>

    <?php if (empty($pedidos)): ?>
        <p>Nenhuma pedido encontrado.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data/Hora</th>
                    <th>Quantidade Total</th>
                    <th>Valor Total (R$)</th>
                    <th>Forma de Pagamento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['pedido_id']) ?></td>
                        <td><?= htmlspecialchars($pedido['data_hora']) ?></td>
                        <td><?= htmlspecialchars($pedido['quantidade_total']) ?></td>
                        <td><?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($pedido['forma_pagamento']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../views/layout/footer.php'; ?>
