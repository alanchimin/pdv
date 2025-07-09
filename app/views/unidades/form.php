<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><?= $isUpdate ? 'Editar Unidade de Medida' : 'Cadastrar Unidade de Medida' ?></h2>
        <a href="/unidadeMedida" class="btn btn-outline-secondary">← Voltar</a>
    </div>

    <form method="POST" action="/unidadeMedida/store">
        <?php if ($isUpdate): ?>
            <input type="hidden" name="unidade_medida_id" value="<?= $unidade['unidade_medida_id'] ?>">
            <script>
                window.updateData = <?= json_encode($unidade, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
            </script>
        <?php endif; ?>

        <div class="row g-3">
            <!-- Nome -->
            <div class="col-xs-12 col-md-6">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <!-- Símbolo -->
            <div class="col-xs-12 col-md-6">
                <label for="simbolo" class="form-label">Símbolo:</label>
                <input type="text" name="simbolo" id="simbolo" class="form-control" required maxlength="10">
            </div>
        </div>

        <!-- Botões -->
        <div class="d-flex justify-content-end mt-4">
            <a href="/unidadeMedida" class="btn btn-secondary me-2">Cancelar</a>
            <button class="btn btn-success">Salvar Unidade</button>
        </div>
    </form>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/unidades/form.js"></script>
