class UnidadeListagem {
    constructor() {
        this.$ctrl = $('#unidade-container');
        this.$inputBusca = this.$ctrl.find('input[name="q"]');
        this.ordem = 'unidade_medida_id';
        this.direcao = 'desc';
        this.pagina = 1;

        this.idExcluir = null;

        this.listen();
        this.buscar();
    }

    listen() {
        this.$inputBusca.on('input', Utils.debounce(() => {
            this.pagina = 1;
            this.buscar();
        }));

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

        this.$ctrl.on('click', '.nav-pagination a', (e) => {
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
                url: `/unidadeMedida/delete/${this.idExcluir}?ajax=1`,
                method: 'POST',
                success: () => {
                    this.idExcluir = null;
                    this.modalExcluir.hide();
                    this.buscar();
                },
                error: () => {
                    Utils.showToast('Erro ao excluir a unidade de medida.');
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
        $.get('/unidadeMedida', {
            q: this.$inputBusca.val(),
            pagina: this.pagina,
            ordem: this.ordem,
            direcao: this.direcao,
            ajax: 1
        }, (html) => {
            const $html = $(html);
            this.$ctrl.find('#tabela-unidades').html($html.html());
            this.updateSortIcons();
        });
    }
}

let unidadeListagem;
$(() => unidadeListagem = new UnidadeListagem());
