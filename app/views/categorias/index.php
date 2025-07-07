<?php include '../views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Categorias</h1>
    <a href="index.php?c=categoria&a=create" class="btn btn-primary mb-3">Nova Categoria</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= $categoria['categoria_id'] ?></td>
                    <td><?= htmlspecialchars($categoria['nome']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../views/layout/footer.php'; ?>
