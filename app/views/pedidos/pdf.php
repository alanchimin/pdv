<?php
/** @var int $pedido_id */
/** @var string $forma_pagamento */
/** @var array $itens */

$subtotal = 0;
$descontos = 0;
$larguraMaxima = 300; // largura típica de cupom
?>

<style>
    body {
        font-family: monospace;
        font-size: 12px;
        max-width: <?= $larguraMaxima ?>px;
        margin: 0 auto;
    }

    .center {
        text-align: center;
    }

    .linha {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    .item {
        margin-bottom: 5px;
    }

    .item-header, .item-row {
        display: flex;
        justify-content: space-between;
    }

    .total {
        font-weight: bold;
        font-size: 13px;
    }
</style>

<div class="center">
    <h3>SUPERMERCADO EXEMPLO</h3>
    <p>CNPJ: 00.000.000/0001-00</p>
    <p>Pedido Nº <?= $pedido_id ?></p>
    <p>Pagamento: <?= htmlspecialchars($forma_pagamento) ?></p>
</div>

<div class="linha"></div>

<div class="item-header">
    <span>Produto</span>
    <span>R$</span>
</div>

<?php foreach ($itens as $item):
    $valorUnitario = $item['valor_unitario'];
    $quantidade = $item['quantidade'];
    $desconto = $item['desconto_valor'];
    $total = $valorUnitario * $quantidade;
    $final = $total - $desconto;
    $subtotal += $total;
    $descontos += $desconto;
?>
<div class="item">
    <div><?= htmlspecialchars($item['nome']) ?></div>
    <div class="item-row">
        <span><?= $quantidade ?> x <?= number_format($valorUnitario, 2, ',', '.') ?></span>
        <span><?= number_format($final, 2, ',', '.') ?></span>
    </div>
    <?php if ($desconto > 0): ?>
        <div class="item-row">
            <small>Desconto</small>
            <small>-<?= number_format($desconto, 2, ',', '.') ?></small>
        </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<div class="linha"></div>

<div class="item-row total">
    <span>Subtotal</span>
    <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
</div>
<div class="item-row total">
    <span>Descontos</span>
    <span>- R$ <?= number_format($descontos, 2, ',', '.') ?></span>
</div>
<div class="item-row total">
    <span>Total</span>
    <span>R$ <?= number_format($subtotal - $descontos, 2, ',', '.') ?></span>
</div>

<div class="linha"></div>

<div class="center">
    <p>Obrigado pela preferência!</p>
    <p><?= date('d/m/Y H:i:s') ?></p>
</div>
