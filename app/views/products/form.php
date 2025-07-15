<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- CSS Personalizado -->
<link rel="stylesheet" href="/css/products/form.css">

<div class="container mt-4">
    <div class="form-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0"><?= $isUpdate ? 'Editar Produto' : 'Cadastrar Produto' ?></h2>
            <a href="/product" class="btn btn-outline-secondary">← Voltar</a>
        </div>

        <form method="POST" action="/product/store" enctype="multipart/form-data">

            <?php if ($isUpdate): ?>
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <script>
                    window.updateData = <?= json_encode($product, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
                </script>
            <?php endif; ?>

            <div class="row g-3">

                <!-- Nome -->
                <div class="col-xs-12 col-md-6">
                    <label for="name" class="form-label">Nome:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <!-- Categoria -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="category_id" class="form-label">Categoria:</label><br>
                    <div class="input-group">
                        <select id="category_id" name="category_id" class="selectpicker" data-live-search="true" required>
                            <option value="">Selecione</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_new_category">+</button>
                    </div>
                </div>

                <!-- Un. Medida -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="unit_id" class="form-label">Un. Medida:</label><br>
                    <div class="input-group">
                        <select id="unit_id" name="unit_id" class="selectpicker" data-live-search="true" required>
                            <option value="">Selecione</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= $unit['unit_id'] ?>">
                                    <?= htmlspecialchars($unit['name']) ?> (<?= $unit['symbol'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_new_unit">+</button>
                    </div>
                </div>

                <!-- Valor Unitário -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="unit_price" class="form-label">Valor Unitário:</label>
                    <div class="input-group">
                        <input type="number" id="unit_price" name="unit_price" step="0.01" class="form-control" required>
                        <span class="input-group-text">R$</span>
                    </div>
                </div>

                <!-- Desconto -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="discount" class="form-label">Desconto:</label>
                    <div class="input-group">
                        <input type="number" id="discount" name="discount" min="0" max="100" value="0" class="form-control">
                        <span class="input-group-text">%</span>
                    </div>
                </div>

                <!-- Tipo de imagem -->
                <div class="col-xs-12">
                    <label class="form-label">Imagem:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="image_type" id="radio_url" value="url" checked>
                        <label class="form-check-label" for="radio_url">URL</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="image_type" id="radio_upload" value="upload">
                        <label class="form-check-label" for="radio_upload">Upload</label>
                    </div>
                </div>

                <!-- URL da imagem -->
                <div class="col-xs-12">
                    <input type="text" name="image_url" id="image_url" class="form-control mt-2" placeholder="http://...">
                </div>

                <!-- Upload da imagem -->
                <div class="col-xs-12">
                    <input type="file" name="image_file" id="image_file" class="form-control mt-2" style="display: none;" accept="image/*">
                </div>
            </div>

            <!-- Preview opcional -->
            <div class="row mt-3">
                <div class="col">
                    <img id="image_preview" src="#" alt="Prévia" style="display:none; max-width:200px;">
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-end mt-4">
                <a href="/product" class="btn btn-secondary me-2">Cancelar</a>
                <button id="btn_save" class="btn btn-success">Salvar Produto</button>
            </div>
        </form>

        <!-- Modal Nova Unidade de Medida -->
        <div class="modal fade" id="modal_new_unit" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Unidade de Medida</h5></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="unit_name" class="form-label">Nome da unidade:</label>
                            <input type="text" class="form-control" id="unit_name" placeholder="Ex: Quilograma">
                        </div>
                        <div>
                            <label for="unit_symbol" class="form-label">Símbolo:</label>
                            <input type="text" class="form-control" id="unit_symbol" placeholder="Ex: kg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btn_save_unit" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nova Categoria -->
        <div class="modal fade" id="modal_new_category" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Nova Categoria</h5></div>
                    <div class="modal-body">
                        <label for="category_name" class="form-label">Nome:</label>
                        <input type="text" class="form-control mb-3" id="category_name" placeholder="Ex: Bebidas">

                        <label for="btn_icon_modal" class="form-label">Ícone:</label>
                        <div class="dropdown w-100 mb-2">
                            <button type="button"
                                    id="btn_icon_modal"
                                    class="btn btn-outline-secondary w-100 text-start d-flex align-items-center justify-content-between"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    aria-expanded="false">
                                <span>
                                    <i id="icon_preview_modal" class="fa-solid fa-house me-2"></i>
                                    <span id="icon_name_modal">house</span>
                                </span>
                                <span class="dropdown-toggle"></span>
                            </button>
                            <div id="dropdown_container_modal" class="dropdown-menu w-100" style="max-height: 300px; overflow-y: auto;">
                                <div class="px-2 py-1">
                                    <input type="text" class="form-control" id="icon_search_modal" placeholder="Buscar ícone...">
                                </div>
                                <ul id="icon_dropdown_modal"></ul>
                            </div>
                        </div>
                        <input type="hidden" name="icon" id="icon_modal" value="fa-solid fa-house">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btn_save_category" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script src="/js/products/form.js"></script>
