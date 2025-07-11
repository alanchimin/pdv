<?php include '../views/layout/header.php'; ?>

<!-- CSS Personalizado -->
<link rel="stylesheet" href="/css/produtos/form.css">

<div class="container mt-4">
    <div class="form-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0"><?= $isUpdate ? 'Editar Produto' : 'Cadastrar Produto' ?></h2>
            <a href="/produto" class="btn btn-outline-secondary">← Voltar</a>
        </div>

        <form method="POST" action="/produto/store" enctype="multipart/form-data">

            <?php if ($isUpdate): ?>
                <input type="hidden" name="produto_id" value="<?= $produto['produto_id'] ?>">
                <script>
                    window.updateData = <?= json_encode($produto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
                </script>
            <?php endif; ?>

            <div class="row g-3">

                <!-- Nome -->
                <div class="col-xs-12 col-md-6">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>

                <!-- Categoria -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="categoria_id" class="form-label">Categoria:</label><br>
                    <div class="input-group">
                        <select id="categoria_id" name="categoria_id" class="selectpicker" data-live-search="true" required>
                            <option value="">Selecione</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['categoria_id'] ?>">
                                    <?= htmlspecialchars($categoria['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_nova_categoria">+</button>
                    </div>
                </div>

                <!-- Un. Medida -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="unidade_medida_id" class="form-label">Un. Medida:</label><br>
                    <div class="input-group">
                        <select id="unidade_medida_id" name="unidade_medida_id" class="selectpicker" data-live-search="true" required>
                            <option value="">Selecione</option>
                            <?php foreach ($unidades as $unidade): ?>
                                <option value="<?= $unidade['unidade_medida_id'] ?>">
                                    <?= htmlspecialchars($unidade['nome']) ?> (<?= $unidade['simbolo'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_nova_unidade_medida">+</button>
                    </div>
                </div>

                <!-- Valor Unitário -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="valor_unitario" class="form-label">Valor Unitário:</label>
                    <div class="input-group">
                        <input type="number" id="valor_unitario" name="valor_unitario" step="0.01" class="form-control" required>
                        <span class="input-group-text">R$</span>
                    </div>
                </div>

                <!-- Desconto -->
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <label for="desconto" class="form-label">Desconto:</label>
                    <div class="input-group">
                        <input type="number" id="desconto" name="desconto" min="0" max="100" value="0" class="form-control">
                        <span class="input-group-text">%</span>
                    </div>
                </div>

                <!-- Tipo de imagem -->
                <div class="col-xs-12">
                    <label class="form-label">Imagem:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_imagem" id="radio-url" value="url" checked>
                        <label class="form-check-label" for="radio-url">URL</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_imagem" id="radio-upload" value="upload">
                        <label class="form-check-label" for="radio-upload">Upload</label>
                    </div>
                </div>

                <!-- URL da imagem -->
                <div class="col-xs-12">
                    <input type="text" name="imagem_url" id="imagem-url" class="form-control mt-2" placeholder="http://...">
                </div>

                <!-- Upload da imagem -->
                <div class="col-xs-12">
                    <input type="file" name="imagem_arquivo" id="imagem-upload" class="form-control mt-2" style="display: none;" accept="image/*">
                </div>
            </div>

            <!-- Preview opcional -->
            <div class="row mt-3">
                <div class="col">
                    <img id="imagem-preview" src="#" alt="Prévia" style="display:none; max-width:200px;">
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-end mt-4">
                <a href="/produto" class="btn btn-secondary me-2">Cancelar</a>
                <button id="btn-salvar" class="btn btn-success">Salvar Produto</button>
            </div>
        </form>

        <!-- Modal Nova Unidade de Medida -->
        <div class="modal fade" id="modal_nova_unidade_medida" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Unidade de Medida</h5></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nova_unidade_nome" class="form-label">Nome da unidade:</label>
                            <input type="text" class="form-control" id="nova_unidade_nome" placeholder="Ex: Quilograma">
                        </div>
                        <div>
                            <label for="nova_unidade_simbolo" class="form-label">Símbolo:</label>
                            <input type="text" class="form-control" id="nova_unidade_simbolo" placeholder="Ex: kg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btn-salvar-unidade-medida" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nova Categoria -->
        <div class="modal fade" id="modal_nova_categoria" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Nova Categoria</h5></div>
                    <div class="modal-body">
                        <label for="nova_categoria_nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control mb-3" id="nova_categoria_nome" name="nome" placeholder="Ex: Bebidas">

                        <label for="icone-btn-modal" class="form-label">Ícone:</label>
                        <div class="dropdown w-100 mb-2">
                            <button type="button"
                                    id="icone-btn-modal"
                                    class="btn btn-outline-secondary w-100 text-start d-flex align-items-center justify-content-between"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    aria-expanded="false">
                                <span>
                                    <i id="icone-preview-modal" class="fa-solid fa-tag me-2"></i>
                                    <span id="icone-nome-modal">house</span>
                                </span>
                                <span class="dropdown-toggle"></span>
                            </button>
                            <div id="dropdown-container-modal" class="dropdown-menu w-100" style="max-height: 300px; overflow-y: auto;">
                                <div class="px-2 py-1">
                                    <input type="text" class="form-control" id="icone-search-modal" placeholder="Buscar ícone...">
                                </div>
                                <ul id="icone-dropdown-modal"></ul>
                            </div>
                        </div>
                        <input type="hidden" name="icone" id="icone-modal" value="fa-solid fa-tag">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btn-salvar-categoria" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/produtos/form.js"></script>
