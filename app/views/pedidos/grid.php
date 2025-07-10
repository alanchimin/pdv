<div id="produtos-grid-content">
    <div class="row" id="grid-produtos">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 produto-item"
                    data-id="<?= $produto['produto_id'] ?>"
                    data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                    data-valor="<?= $produto['valor_unitario'] ?>"
                    data-categoria-id="<?= $produto['categoria_id'] ?>"
                    data-unidade="<?= htmlspecialchars($produto['simbolo']) ?>">

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
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php 
        $baseUrl = '/pedido/grid';
        include __DIR__ . '/../components/pagination.php'; 
    ?>
</div>
