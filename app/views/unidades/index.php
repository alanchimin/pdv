<?php include '../app/views/layout/header.php'; ?>

<div class="container mt-4">
    <h1>Unidades de Medida</h1>
    <a href="index.php?c=unidademedida&a=create" class="btn btn-primary mb-3">Nova Unidade</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Símbolo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($unidades as $unidade): ?>
                <tr>
                    <td><?= $unidade['unidade_medida_id'] ?></td>
                    <td><?= htmlspecialchars($unidade['nome']) ?></td>
                    <td><?= htmlspecialchars($unidade['simbolo']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../app/views/layout/footer.php'; ?>
