<?php include '../app/views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Nova Unidade de Medida</h1>
    <form method="POST" action="index.php?c=unidademedida&a=store">
        <div class="mb-3">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="simbolo">Símbolo:</label>
            <input type="text" name="simbolo" id="simbolo" class="form-control" required maxlength="10">
        </div>
        <button class="btn btn-success">Salvar</button>
    </form>
</div>

<?php include '../app/views/layout/footer.php'; ?>
