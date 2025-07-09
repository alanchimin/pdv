<?php include '../views/layout/header.php'; ?>
<!-- CSS Personalizado -->
<link rel="stylesheet" href="/css/pedidos/index.css">

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Painel esquerdo: Produtos disponíveis -->
        <div class="col-md-8">
            <!-- Campo de pesquisa -->
            <div class="mb-3">
                <input type="text" id="busca-produto" class="form-control" placeholder="Buscar produto...">
            </div>

            <!-- Lista horizontal de categorias -->
            <div class="mb-3 overflow-auto">
                <div class="btn-group" role="group" aria-label="Categorias">
                    <button type="button" class="btn btn-outline-secondary active" data-categoria-id="0">Todas</button>
                    <?php foreach ($categorias as $cat): ?>
                        <button type="button" class="btn btn-outline-secondary" data-categoria-id="<?= $cat['categoria_id'] ?>">
                            <?= htmlspecialchars($cat['nome']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Grid de produtos -->
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

                            <div class="card-body d-flex align-items-center justify-content-center" style="height: 60px;">
                                <h5 class="card-title text-center mb-0" style="font-size: 1rem;"><?= htmlspecialchars($produto['nome']) ?></h5>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Painel direito: Carrinho -->
        <div class="col-md-4">
            <h4>Itens do Pedido</h4>

            <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                <ul class="list-group mb-3" id="lista-itens"></ul>
                <div id="lista-placeholder" class="text-muted text-center py-3">Nenhum item adicionado.</div>
            </div>

            <table class="table table-bordered mt-3">
                <tr>
                    <th>Subtotal</th>
                    <td id="pedido-subtotal">R$ 0,00</td>
                </tr>
                <tr>
                    <th>Descontos</th>
                    <td id="pedido-descontos">R$ 0,00</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td id="pedido-total">R$ 0,00</td>
                </tr>
            </table>

            <button class="btn btn-outline-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalLimparPedido">Limpar Itens do Pedido</button>

            <div id="mensagem-finalizar" class="alert alert-danger d-none" role="alert"></div>
            <button class="btn btn-primary w-100" id="btn-finalizar-pedido">Finalizar Pedido</button>
        </div>
    </div>
</div>

<!-- Modal de quantidade e desconto -->
<div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="modalProdutoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProdutoLabel">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-adiciona-produto">
                    <div id="mensagem-erro" class="alert alert-danger d-none" role="alert"></div>
                    <input type="hidden" id="produto-id">
                    <div class="mb-3">
                        <label for="produto-nome" class="form-label">Produto</label>
                        <input type="text" class="form-control" id="produto-nome" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" required min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Desconto</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo-desconto" id="desconto-percentual-radio" value="percentual" checked>
                            <label class="form-check-label" for="desconto-percentual-radio">%</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo-desconto" id="desconto-reais-radio" value="reais">
                            <label class="form-check-label" for="desconto-reais-radio">R$</label>
                        </div>
                    </div>
                    <div class="mb-3" id="campo-desconto-percentual">
                        <label for="desconto-porcentagem" class="form-label">Desconto (%)</label>
                        <input type="number" class="form-control" id="desconto-porcentagem" min="0" max="100" value="0">
                    </div>
                    <div class="mb-3 d-none" id="campo-desconto-reais">
                        <label for="desconto-reais" class="form-label">Desconto (R$)</label>
                        <input type="number" class="form-control" id="desconto-reais" min="0" step="0.01" value="0.00">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-adicionar">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLimparPedido" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Confirmar Limpeza</h5></div>
            <div class="modal-body">
                Deseja realmente limpar todos os itens do pedido?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="btn-confirmar-limpeza" data-bs-dismiss="modal">Limpar</button>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/pedidos/index.js"></script>
