<?php include '../views/layout/header.php'; ?>

<div id="categoria-container">
    <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
        <div class="flex-grow-1">
            <h2 class="mb-2">Categorias</h2>
            <form method="GET" action="/categoria" class="d-flex">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar categoria...">
            </form>
        </div>
        <div>
            <a href="/categoria/form" class="btn btn-success">Nova Categoria</a>
        </div>
    </div>

    <?php include 'table.php'; ?>

    <!-- Modal confirmação exclusão -->
    <div class="modal fade" id="modal_confirmar_exclusao" tabindex="-1" aria-labelledby="modal_confirmar_exclusao_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_confirmar_exclusao_label">Confirmar exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div id="modal_confirmar_exclusao_erro" class="mt-3"></div>
                    Deseja realmente excluir esta categoria?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="btn_confirmar_excluir" type="button" class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layout/footer.php'; ?>

<script src="/js/categorias/index.js"></script>
