<?php
/** @var int $pedido_id */
/** @var string $forma_pagamento */
/** @var array $itens */

$subtotal = 0;
$descontos = 0;
$larguraMaxima = 300;
?>

<!-- CSS -->
<style>
    <?php include __DIR__ . '/../../public/css/pedidos/pdf.css'; ?>
</style>

<div class="cupom" style="max-width: <?= $larguraMaxima ?>px;">
    <div class="center">
        <h3>SUPERMERCADO EXEMPLO</h3>
        <p>CNPJ: 00.000.000/0001-00</p>
        <p>Pedido Nº <?= $pedido_id ?></p>
        <p>Pagamento: <?= htmlspecialchars($forma_pagamento) ?></p>
    </div>

    <div class="linha"></div>

    <table class="tabela">
        <thead>
            <tr class="bold">
                <td>Produto</td>
                <td class="right">R$</td>
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
                <td colspan="2"><?= htmlspecialchars($item['nome']) ?></td>
            </tr>
            <tr>
                <td><?= $quantidade ?> x <?= number_format($valorUnitario, 2, ',', '.') ?></td>
                <td class="right"><?= number_format($total, 2, ',', '.') ?></td>
            </tr>
            <?php if ($desconto > 0): ?>
            <tr class="small">
                <td>Desconto</td>
                <td class="right">-<?= number_format($desconto, 2, ',', '.') ?></td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bold">
            <tr>
                <td>Subtotal</td>
                <td class="right">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Descontos</td>
                <td class="right">- R$ <?= number_format($descontos, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Total</td>
                <td class="right">R$ <?= number_format($subtotal - $descontos, 2, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="linha"></div>

    <div class="center">
        <p>Obrigado pela preferência!</p>
        <p><?= date('d/m/Y H:i:s') ?></p>
    </div>
</div>
