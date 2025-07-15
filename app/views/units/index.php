<?php include __DIR__ . '/../layout/header.php'; ?>

<div id="unit-container" class="list-wrapper">
    <div class="row mb-3 align-items-end">
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            <h2 class="mb-2">Unidades de Medida</h2>
            <form method="GET" action="/unit" class="d-flex">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar unidade...">
            </form>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 text-md-end mt-2 mt-md-0">
            <a href="/unit/form" class="btn btn-success w-100 w-md-auto">Nova Unidade</a>
        </div>
    </div>

    <?php include __DIR__ . '/table.php'; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script src="/js/units/index.js"></script>
