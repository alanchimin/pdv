<?php include '../app/views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Comandas</h1>
    <a href="index.php?c=comanda&a=create" class="btn btn-primary mb-3">Nova Comanda</a>

    <?php if (empty($comandas)): ?>
        <p>Nenhuma comanda encontrada.</p>
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
                <?php foreach ($comandas as $comanda): ?>
                    <tr>
                        <td><?= htmlspecialchars($comanda['comanda_id']) ?></td>
                        <td><?= htmlspecialchars($comanda['data_hora']) ?></td>
                        <td><?= htmlspecialchars($comanda['quantidade_total']) ?></td>
                        <td><?= number_format($comanda['valor_total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($comanda['forma_pagamento']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../app/views/layout/footer.php'; ?>
