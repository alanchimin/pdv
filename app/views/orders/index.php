<?php include '../views/layout/header.php'; ?>

<!-- CSS Personalizado -->
<link rel="stylesheet" href="/css/orders/index.css">

<div class="container-fluid mt-4">
    <div class="gy-4">
        <div class="row d-md-flex">
            <div class="col-md-8" id="categories_products_container">
                <div class="row">
                    <!-- Categorias - Horizontal (xs/sm/md) -->
                    <div class="col-12 d-lg-none">
                        <div class="list-group list-group-horizontal overflow-auto mb-2 gap-2 flex-nowrap" id="horizontal_categories">
                            <button type="button" class="category-item btn btn-outline-secondary active" data-category-id="0">
                                <i class="fa-solid fa-house me-2"></i> Todas
                            </button>
                            <?php foreach ($categories as $cat): ?>
                                <button type="button" class="category-item btn btn-outline-secondary" data-category-id="<?= $cat['category_id'] ?>">
                                    <?php if (!empty($cat['icon'])): ?>
                                        <i class="<?= htmlspecialchars($cat['icon']) ?> me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Categorias - Vertical (lg) -->
                    <div class="col-3 d-none d-lg-block">
                        <div class="list-group mb-3 gap-1">
                            <button type="button" class="category-item btn btn-outline-secondary active" data-category-id="0">
                                <i class="fa-solid fa-house me-2"></i> Todas
                            </button>
                            <?php foreach ($categories as $cat): ?>
                                <button type="button" class="category-item btn btn-outline-secondary" data-category-id="<?= $cat['category_id'] ?>">
                                    <?php if (!empty($cat['icon'])): ?>
                                        <i class="<?= htmlspecialchars($cat['icon']) ?> me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Produtos -->
                    <div class="col-12 col-lg-9">
                        <!-- Campo de pesquisa -->
                        <div class="mb-3">
                            <input type="text" id="search" class="form-control" placeholder="Buscar produto...">
                        </div>

                        <!-- Grid de produtos -->
                        <div id="products_grid">
                            <div class="text-center my-5">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carrinho -->
            <div id="cart_container" class="col-md-4 d-none d-md-block">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fa-solid fa-shopping-cart me-2"></i>Carrinho</h4>
                    <a id="btn_back_from_cart" href="#" class="btn btn-outline-secondary d-md-none">← Voltar</a>
                </div>

                <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group mb-3" id="cart_items"></ul>
                    <div id="empty_cart" class="text-muted text-center py-3">Nenhum item adicionado.</div>
                </div>

                <table class="table table-bordered mt-3">
                    <tr>
                        <th>Subtotal</th>
                        <td id="order_subtotal">R$ 0,00</td>
                    </tr>
                    <tr>
                        <th>Descontos</th>
                        <td id="order_discounts">R$ 0,00</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td id="order_total">R$ 0,00</td>
                    </tr>
                </table>

                <button class="btn btn-outline-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modal_clear_cart">Limpar Carrinho</button>
                <button class="btn btn-primary w-100" id="btn_checkout">Finalizar Pedido</button>
            </div>
        </div>

        <!-- Botão flutuante do carrinho (xs/sm) -->
        <button id="btn_floating_cart" class="btn btn-primary rounded-circle d-md-none position-fixed"
                style="bottom: 20px; right: 20px; width: 56px; height: 56px; z-index: 1050;">
            <i class="fa-solid fa-shopping-cart"></i>
        </button>
    </div>
</div>

<!-- Modal de quantidade e desconto -->
<div class="modal fade" id="modal_product" tabindex="-1" aria-labelledby="modal_product_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_product_label">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="product_id">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Produto:</label>
                        <input type="text" class="form-control" id="product_name" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Quantidade:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="amount" required min="1" value="1">
                            <span class="input-group-text" id="unit">und</span>
                        </div>
                    </div>
                    <div id="discount_container" class="mb-3">
                        <label for="discount" class="form-label">Desconto:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="discount" disabled>
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
                <button type="button" class="btn btn-success" id="btn_add_product">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Limpeza do Carrinho -->
<div class="modal fade" id="modal_clear_cart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Confirmar Limpeza</h5></div>
            <div class="modal-body">
                Deseja realmente remover todos os itens do carrinho?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="btn_confirm_clear_cart" data-bs-dismiss="modal">Limpar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Pedido -->
<div class="modal fade" id="modal_confirm_order" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <table class="table table-sm mb-0">
                        <tbody></tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label for="payment_method_id" class="form-label">Forma de Pagamento:</label>
                    <select class="form-select" id="payment_method_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($paymentMethods as $pm): ?>
                        <option value="<?= $pm['payment_method_id'] ?>">
                            <?= htmlspecialchars($pm['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_confirm_order">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/orders/index.js"></script>
