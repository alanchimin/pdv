class ProdutoListagem {
    constructor() {
        this.$ctrl = $('#produto-container');
        this.$inputBusca = this.$ctrl.find('input[name="q"]');
        this.ordem = 'produto_id';
        this.direcao = 'desc';
        this.pagina = 1;
        this.timer = null;

        this.idExcluir = null;

        this.$modalConfirmarExclusaoErro = this.$ctrl.find('#modal_confirmar_exclusao_erro');

        this.listen();
        this.buscar();
    }

    listen() {
        this.$inputBusca.on('input', () => {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.pagina = 1;
                this.buscar();
            }, 300);
        });

        this.$ctrl.on('click', 'th.sortable', (e) => {
            const $th = $(e.currentTarget);
            const campo = $th.data('campo');

            if (this.ordem === campo) {
                this.direcao = this.direcao === 'asc' ? 'desc' : 'asc';
            } else {
                this.ordem = campo;
                this.direcao = 'asc';
            }

            this.updateSortIcons();
            this.buscar();
        });

        this.$ctrl.on('click', '.paginacao a', (e) => {
            e.preventDefault();
            const $a = $(e.currentTarget);
            const pagina = parseInt($a.data('pagina'));
            if (!isNaN(pagina)) {
                this.pagina = pagina;
                this.buscar();
            }
        });

        this.$ctrl.on('click', '.btn-excluir', (e) => {
            const $btn = $(e.currentTarget);
            this.idExcluir = $btn.data('id');
            this.modalExcluir = new bootstrap.Modal(document.getElementById('modal_confirmar_exclusao'));
            this.modalExcluir.show();
        });

        this.$ctrl.on('click', '#btn_confirmar_excluir', () => {
            if (!this.idExcluir) return;
            $.ajax({
                url: `/produto/delete/${this.idExcluir}?ajax=1`,
                method: 'POST',
                success: () => {
                    this.idExcluir = null;
                    this.modalExcluir.hide();
                    this.buscar();
                },
                error: () => {
                    Utils.showAlert(this.$modalConfirmarExclusaoErro, 'Erro ao excluir o produto.');
                }
            });
        });
    }

    updateSortIcons() {
        this.$ctrl.find('th.sortable').each((_, th) => {
            const $th = $(th);
            const campo = $th.data('campo');
            $th.removeClass('sorted-asc sorted-desc');
            if (campo === this.ordem) {
                $th.addClass(`sorted-${this.direcao}`);
            }
        });
    }

    buscar() {
        $.get('/produto', {
            q: this.$inputBusca.val(),
            pagina: this.pagina,
            ordem: this.ordem,
            direcao: this.direcao,
            ajax: 1
        }, (html) => {
            const $html = $(html);
            this.$ctrl.find('#tabela-produtos').html($html.html());
            this.updateSortIcons();
        });
    }
}

let produtoListagem;
$(() => produtoListagem = new ProdutoListagem());
