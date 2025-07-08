<?php
/** @var int $pedido_id */
/** @var string $forma_pagamento */
/** @var array $itens */

$subtotal = 0;
$descontos = 0;
?>
<h2>Pedido #<?= $pedido_id ?></h2>
<p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($forma_pagamento) ?></p>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Qtd</th>
            <th>Valor Unitário</th>
            <th>Desconto</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($itens as $item):
            $valorUnitario = $item['valor_unitario'];
            $quantidade = $item['quantidade'];
            $desconto = $item['desconto_valor'];
            $total = $valorUnitario * $quantidade;
            $final = $total - $desconto;
            $subtotal += $total;
            $descontos += $desconto;
        ?>
        <tr>
            <td><?= htmlspecialchars($item['nome']) ?></td>
            <td><?= $quantidade ?></td>
            <td>R$ <?= number_format($valorUnitario, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($desconto, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($final, 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
<p><strong>Descontos:</strong> R$ <?= number_format($descontos, 2, ',', '.') ?></p>
<p><strong>Total:</strong> R$ <?= number_format($subtotal - $descontos, 2, ',', '.') ?></p>
