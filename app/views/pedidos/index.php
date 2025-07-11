<?php include '../views/layout/header.php'; ?>

<!-- CSS Personalizado -->
<link rel="stylesheet" href="/css/pedidos/index.css">

<div class="container-fluid mt-4">
    <div class="gy-4">
        <div class="row d-md-flex" id="painel-principal">
            <div class="col-md-8" id="categorias-produtos-container">
                <div class="row">
                    <!-- Categorias - Horizontal (xs/sm/md) -->
                    <div class="col-12 d-lg-none">
                        <div class="list-group list-group-horizontal overflow-auto mb-2 gap-2 flex-nowrap" id="categorias-horizontal">
                            <button type="button" class="categoria-item btn btn-outline-secondary active" data-categoria-id="0">
                                <i class="fa-solid fa-house me-2"></i> Todas
                            </button>
                            <?php foreach ($categorias as $cat): ?>
                                <button type="button" class="categoria-item btn btn-outline-secondary" data-categoria-id="<?= $cat['categoria_id'] ?>">
                                    <?php if (!empty($cat['icone'])): ?>
                                        <i class="<?= htmlspecialchars($cat['icone']) ?> me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Categorias - Vertical (lg) -->
                    <div class="col-3 d-none d-lg-block">
                        <div class="list-group mb-3 gap-1" id="categorias-vertical">
                            <button type="button" class="categoria-item btn btn-outline-secondary active" data-categoria-id="0">
                                <i class="fa-solid fa-house me-2"></i> Todas
                            </button>
                            <?php foreach ($categorias as $cat): ?>
                                <button type="button" class="categoria-item btn btn-outline-secondary" data-categoria-id="<?= $cat['categoria_id'] ?>">
                                    <?php if (!empty($cat['icone'])): ?>
                                        <i class="<?= htmlspecialchars($cat['icone']) ?> me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Produtos -->
                    <div class="col-12 col-lg-9">
                        <!-- Campo de pesquisa -->
                        <div class="mb-3">
                            <input type="text" id="busca-produto" class="form-control" placeholder="Buscar produto...">
                        </div>

                        <!-- Grid de produtos -->
                        <div id="produtos-grid-content">
                            <div class="text-center my-5">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carrinho -->
            <div id="carrinho-container" class="col-md-4 d-none d-md-block">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fa-solid fa-shopping-cart me-2"></i>Carrinho</h4>
                    <a id="carrinho-btn-voltar" href="#" class="btn btn-outline-secondary d-md-none">← Voltar</a>
                </div>

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

                <button class="btn btn-outline-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalLimparCarrinho">Limpar Carrinho</button>

                <div id="mensagem-finalizar" class="alert alert-danger d-none" role="alert"></div>
                <button class="btn btn-primary w-100" id="btn-finalizar-pedido">Finalizar Pedido</button>
            </div>
        </div>

        <!-- Botão flutuante do carrinho (xs/sm) -->
        <button id="btn-carrinho" class="btn btn-primary rounded-circle d-md-none position-fixed"
                style="bottom: 20px; right: 20px; width: 56px; height: 56px; z-index: 1050;">
            <i class="fa-solid fa-shopping-cart"></i>
        </button>
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
                        <label for="produto-nome" class="form-label">Produto:</label>
                        <input type="text" class="form-control" id="produto-nome" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="quantidade" required min="1" value="1">
                            <span class="input-group-text" id="unidade-label">und</span>
                        </div>
                    </div>
                    <div id="desconto-container" class="mb-3">
                        <label for="desconto" class="form-label">Desconto:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="desconto" disabled>
                            <span class="input-group-text">R$</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="total" disabled>
                            <span class="input-group-text">R$</span>
                        </div>
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

<!-- Modal de Confirmação de Limpeza do Carrinho -->
<div class="modal fade" id="modalLimparCarrinho" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Confirmar Limpeza</h5></div>
            <div class="modal-body">
                Deseja realmente remover todos os itens do carrinho?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="btn-confirmar-limpeza" data-bs-dismiss="modal">Limpar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Pedido -->
<div class="modal fade" id="modalConfirmarPedido" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <table class="table table-sm mb-0">
                        <tbody id="resumo-itens-lista"></tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label for="forma_pagamento_id" class="form-label">Forma de Pagamento:</label>
                    <select class="form-select" id="forma_pagamento_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($formasPagamento as $fp): ?>
                        <option value="<?= $fp['forma_pagamento_id'] ?>">
                            <?= htmlspecialchars($fp['nome']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="alert alert-danger d-none" id="erro-confirmacao-pedido"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-confirmar-pedido">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/pedidos/index.js"></script>
