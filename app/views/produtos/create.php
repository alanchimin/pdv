<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Cadastrar Produto</h2>
        <a href="/produto" class="btn btn-outline-secondary">← Voltar</a>
    </div>

    <form method="POST" action="/produto/store" enctype="multipart/form-data">
        <div class="row g-3">

            <!-- Nome -->
            <div class="col-xs-12 col-md-6 col-lg-4">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <!-- Valor Unitário -->
            <div class="col-xs-12 col-md-6 col-lg-2">
                <label for="valor_unitario" class="form-label">Valor Unitário:</label>
                <input type="number" name="valor_unitario" step="0.01" class="form-control" required>
            </div>

            <!-- Un. Medida -->
            <div class="col-xs-12 col-md-6 col-lg-3">
                <label for="unidade_medida_id" class="form-label">Un. Medida:</label><br>
                <select name="unidade_medida_id" class="selectpicker" data-live-search="true" required>
                    <option value="">Selecione</option>
                    <?php foreach ($unidades as $unidade): ?>
                        <option value="<?= $unidade['unidade_medida_id'] ?>">
                            <?= htmlspecialchars($unidade['nome']) ?> (<?= $unidade['simbolo'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Categoria -->
            <div class="col-xs-12 col-md-6 col-lg-3">
                <label for="categoria_id" class="form-label">Categoria:</label><br>
                <select name="categoria_id" class="selectpicker" data-live-search="true" required>
                    <option value="">Selecione</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['categoria_id'] ?>">
                            <?= htmlspecialchars($categoria['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
            <button class="btn btn-success">Salvar Produto</button>
        </div>
    </form>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/common/form-handlers.js"></script>
<script src="/js/produtos/create.js"></script>
