<div id="products_grid">
    <div class="row" id="products_grid_content">
        <?php foreach ($products as $product): ?>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 product-item"
                    data-id="<?= $product['product_id'] ?>"
                    data-name="<?= htmlspecialchars($product['name']) ?>"
                    data-unit-price="<?= $product['unit_price'] ?>"
                    data-category-id="<?= $product['category_id'] ?>"
                    data-unit="<?= htmlspecialchars($product['symbol']) ?>"
                    data-discount="<?= $product['discount'] ?>">

                    <img 
                        src="<?= htmlspecialchars($product['image'] ?? '/images/placeholder.svg') ?>" 
                        class="card-img-top product-img" 
                        alt="<?= htmlspecialchars($product['name']) ?>"
                        onerror="this.onerror=null; this.src='/images/placeholder.svg';">

                    <div class="card-body d-flex align-items-center justify-content-center">
                        <h5 class="card-title text-center mb-0"
                            title="<?= htmlspecialchars($product['name']) ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </h5>
                    </div>

                    <div class="card-footer text-center p-2">
                        <?php
                            $unitPrice = (float) $product['unit_price'];
                            $discount = (float) ($product['discount'] ?? 0);
                            $total = max(0, $unitPrice - ($unitPrice * ($discount / 100)));
                        ?>

                        <?php if ($discount > 0): ?>
                            <div class="small text-muted text-decoration-line-through" style="line-height: 1;">
                                De: R$ <?= number_format($unitPrice, 2, ',', '.') ?>
                            </div>
                            <div class="fw-semibold text-success" style="font-size: 1rem; line-height: 1.1;">
                                Por: R$ <?= number_format($total, 2, ',', '.') ?>
                            </div>
                        <?php else: ?>
                            <div class="fw-semibold text-dark" style="font-size: 1rem;">
                                R$ <?= number_format($unitPrice, 2, ',', '.') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php 
        $baseUrl = '/order/grid';
        include __DIR__ . '/../components/pagination.php'; 
    ?>
</div>
