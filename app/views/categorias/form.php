<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Nova Categoria</h1>
    <form method="POST" action="/categoria/store">
        <div class="mb-3">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <button class="btn btn-success">Salvar</button>
    </form>
</div>

<?php include '../views/layout/footer.php'; ?>
