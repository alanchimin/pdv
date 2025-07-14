<?php
/** @var int $orderId */
/** @var string $paymentMethod */
/** @var array $items */

$subtotal = 0;
$discounts = 0;
$maxWidth = 300;
?>

<!-- CSS -->
<style>
    <?php include __DIR__ . '/../../public/css/orders/pdf.css'; ?>
</style>

<div class="ticket" style="max-width: <?= $maxWidth ?>px;">
    <div class="center">
        <h3>SUPERMERCADO EXEMPLO</h3>
        <p>CNPJ: 00.000.000/0001-00</p>
        <p>Pedido Nº <?= $orderId ?></p>
        <p>Usuário: <?= $user ?></p>
        <p>Pagamento: <?= htmlspecialchars($paymentMethod) ?></p>
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr class="bold">
                <td>Produto</td>
                <td class="right">R$</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item):
                $unitPrice = $item['unit_price'];
                $amount = $item['amount'];
                $discount = $item['discount'];
                $total = $unitPrice * $amount;
                $final = $total - $discount;
                $subtotal += $total;
                $discounts += $discount;
            ?>
            <tr>
                <td colspan="2"><?= htmlspecialchars($item['name']) ?></td>
            </tr>
            <tr>
                <td><?= $amount ?> x <?= number_format($unitPrice, 2, ',', '.') ?></td>
                <td class="right"><?= number_format($total, 2, ',', '.') ?></td>
            </tr>
            <?php if ($discount > 0): ?>
            <tr class="small">
                <td>Desconto</td>
                <td class="right">-<?= number_format($discount, 2, ',', '.') ?></td>
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
                <td class="right">- R$ <?= number_format($discounts, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Total</td>
                <td class="right">R$ <?= number_format($subtotal - $discounts, 2, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>Obrigado pela preferência!</p>
        <p><?= date('d/m/Y H:i:s') ?></p>
    </div>
</div>
