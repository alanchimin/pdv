<div id="produtos-grid-content">
    <div class="row" id="grid-produtos">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 produto-item"
                    data-id="<?= $produto['produto_id'] ?>"
                    data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                    data-valor="<?= $produto['valor_unitario'] ?>"
                    data-categoria-id="<?= $produto['categoria_id'] ?>"
                    data-unidade="<?= htmlspecialchars($produto['simbolo']) ?>"
                    data-desconto="<?= $produto['desconto'] ?>">

                    <img 
                        src="<?= htmlspecialchars($produto['imagem'] ?? '/images/placeholder.svg') ?>" 
                        class="card-img-top produto-img" 
                        alt="<?= htmlspecialchars($produto['nome']) ?>"
                        onerror="this.onerror=null; this.src='/images/placeholder.svg';">

                    <div class="card-body d-flex align-items-center justify-content-center">
                        <h5 class="card-title text-center mb-0"
                            title="<?= htmlspecialchars($produto['nome']) ?>">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </h5>
                    </div>

                    <div class="card-footer text-center p-2">
                        <?php
                            $valor = (float) $produto['valor_unitario'];
                            $desconto = (float) ($produto['desconto'] ?? 0);
                            $valorFinal = max(0, $valor - ($valor * ($desconto / 100)));
                        ?>

                        <?php if ($desconto > 0): ?>
                            <div class="small text-muted text-decoration-line-through" style="line-height: 1;">
                                De: R$ <?= number_format($valor, 2, ',', '.') ?>
                            </div>
                            <div class="fw-semibold text-success" style="font-size: 1rem; line-height: 1.1;">
                                Por: R$ <?= number_format($valorFinal, 2, ',', '.') ?>
                            </div>
                        <?php else: ?>
                            <div class="fw-semibold text-dark" style="font-size: 1rem;">
                                R$ <?= number_format($valor, 2, ',', '.') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php 
        $baseUrl = '/pedido/grid';
        include __DIR__ . '/../components/pagination.php'; 
    ?>
</div>
