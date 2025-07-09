<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="form-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0"><?= $isUpdate ? 'Editar Categoria' : 'Cadastrar Categoria' ?></h2>
            <a href="/categoria" class="btn btn-outline-secondary">← Voltar</a>
        </div>

        <form method="POST" action="/categoria/store">
            <?php if ($isUpdate): ?>
                <input type="hidden" name="categoria_id" value="<?= $categoria['categoria_id'] ?>">
                <script>
                    window.updateData = <?= json_encode($categoria, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
                </script>
            <?php endif; ?>

            <div class="row g-3">
                <!-- Nome -->
                <div class="col-xs-12 col-md-6">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-end mt-4">
                <a href="/categoria" class="btn btn-secondary me-2">Cancelar</a>
                <button class="btn btn-success">Salvar Categoria</button>
            </div>
        </form>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/categorias/form.js"></script>
