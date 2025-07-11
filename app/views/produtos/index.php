<?php include '../views/layout/header.php'; ?>

<div id="produto-container" class="list-wrapper">
    <div class="row mb-3 align-items-end">
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            <h2 class="mb-2">Produtos</h2>
            <form method="GET" action="/produto" class="d-flex">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar produto...">
            </form>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 text-md-end mt-2 mt-md-0">
            <a href="/produto/form" class="btn btn-success w-100 w-md-auto">Novo Produto</a>
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
                    Deseja realmente excluir este produto?
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

<script src="/js/produtos/index.js"></script>
