<?php include '../app/views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Cadastrar Produto</h1>
    <form method="POST" action="index.php?c=produto&a=store" enctype="multipart/form-data">
        <div class="mb-2">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <script src="js/imagem.js"></script>

        <div class="mb-2">
            <label>Imagem:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_imagem" id="radio-url" value="url" checked>
                <label class="form-check-label" for="radio-url">URL</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_imagem" id="radio-upload" value="upload">
                <label class="form-check-label" for="radio-upload">Upload</label>
            </div>

            <input type="text" name="imagem_url" id="imagem-url" class="form-control mt-2" placeholder="http://...">
            <input type="file" name="imagem_arquivo" id="imagem-upload" class="form-control mt-2" style="display: none;" accept="image/*">

            <img id="imagem-preview" src="#" alt="Prévia" style="display:none; max-width:200px; margin-top:10px;">
        </div>

        <div class="mb-2">
            <label>Unidade de Medida:</label>
            <select name="unidade_medida_id" class="form-control" required>
                <option value="">Selecione...</option>
                <?php foreach ($unidades as $unidade): ?>
                    <option value="<?= $unidade['unidade_medida_id'] ?>">
                        <?= htmlspecialchars($unidade['nome']) ?> (<?= $unidade['simbolo'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-2">
            <label>Valor Unitário:</label>
            <input type="number" name="valor_unitario" step="0.01" class="form-control" required>
        </div>

        <div class="mb-2">
            <label>Categoria:</label>
            <select name="categoria_id" class="form-control" required>
                <option value="">Selecione...</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['categoria_id'] ?>">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-success">Salvar Produto</button>
    </form>
</div>

<?php include '../app/views/layout/footer.php'; ?>
