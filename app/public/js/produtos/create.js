class ProdutoCreate {
    constructor() {
        this.$ctrl = $('.container');
        this.$selects = this.$ctrl.find('.selectpicker');
        this.$imagemUrl = this.$ctrl.find('#imagem-url');
        this.$imagemUpload = this.$ctrl.find('#imagem-upload');
        this.$imagemPreview = this.$ctrl.find('#imagem-preview');
        this.$radioUrl = this.$ctrl.find('#radio-url');
        this.$radioUpload = this.$ctrl.find('#radio-upload');

        this.$selectCategoria = this.$ctrl.find('#categoria_id');
        this.$novaCategoriaNome = this.$ctrl.find('#nova_categoria_nome');
        this.$btnSalvarCategoria = this.$ctrl.find('#btn-salvar-categoria');
        this.$modalNovaCategoriaErro = this.$ctrl.find('#modal_nova_categoria_erro');
        this.modalNovaCategoria = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal_nova_categoria'));

        this.$selectUnidade = this.$ctrl.find('#unidade_medida_id');
        this.$novaUnidadeNome = this.$ctrl.find('#nova_unidade_nome');
        this.$novaUnidadeSimbolo = this.$ctrl.find('#nova_unidade_simbolo');
        this.$btnSalvarUnidade = this.$ctrl.find('#btn-salvar-unidade-medida');
        this.$modalNovaUnidadeMedidaErro = this.$ctrl.find('#modal_nova_unidade_medida_erro');
        this.modalNovaUnidade = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal_nova_unidade_medida'));

        this.urlAtual = '';
        this.uploadAtual = '';

        this.init();
    }

    init() {
        this.initSelects();
        this.initRadioToggle();
        this.initImagemPreview();
        this.initUploadPreview();
        this.initSalvarCategoria();
        this.initSalvarUnidadeMedida();
    }

    initSelects() {
        if (this.$selects.length) {
            this.$selects.selectpicker();
        }
    }

    initRadioToggle() {
        this.$radioUrl.on('change', () => {
            if (this.$radioUrl.is(':checked')) {
                this.$imagemUrl.show();
                this.$imagemUpload.hide();

                if (this.urlAtual) {
                    this.$imagemPreview.attr('src', this.urlAtual).show();
                } else {
                    this.$imagemPreview.hide();
                }
            }
        });

        this.$radioUpload.on('change', () => {
            if (this.$radioUpload.is(':checked')) {
                this.$imagemUpload.show();
                this.$imagemUrl.hide();

                if (this.uploadAtual) {
                    this.$imagemPreview.attr('src', this.uploadAtual).show();
                } else {
                    this.$imagemPreview.hide();
                }
            }
        });
    }

    initImagemPreview() {
        this.$imagemUrl.on('input', () => {
            this.urlAtual = this.$imagemUrl.val();
            if (this.$radioUrl.is(':checked') && this.urlAtual) {
                this.$imagemPreview.attr('src', this.urlAtual).show();
            } else if (this.$radioUrl.is(':checked')) {
                this.$imagemPreview.hide();
            }
        });
    }

    initUploadPreview() {
        this.$imagemUpload.on('change', (e) => {
            const file = e.target.files[0];
            if (!file) {
                this.uploadAtual = '';
                if (this.$radioUpload.is(':checked')) this.$imagemPreview.hide();
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.uploadAtual = e.target.result;
                if (this.$radioUpload.is(':checked')) {
                    this.$imagemPreview.attr('src', this.uploadAtual).show();
                }
            };
            reader.readAsDataURL(file);
        });
    }

    initSalvarCategoria() {
        this.$btnSalvarCategoria.on('click', (e) => {
            e.preventDefault();
            const nome = this.$novaCategoriaNome.val().trim();
            if (!nome) return Utils.showAlert(this.$modalNovaCategoriaErro, 'Informe um nome válido para a categoria.');

            $.post('/categoria/storeAjax', { nome }, (res) => {
                if (res.success && res.categoria) {
                    const nova = res.categoria;
                    Utils.addNewSelectOption(this.$selectCategoria, nova.categoria_id, nova.nome);
                    this.modalNovaCategoria.hide();
                    this.$novaCategoriaNome.val('');
                } else {
                    Utils.showAlert(this.$modalNovaCategoriaErro, 'Erro ao salvar categoria.');
                }
            }, 'json').fail(() => {
                Utils.showAlert(this.$modalNovaCategoriaErro, 'Erro ao salvar categoria.');
            });
        });
    }

    initSalvarUnidadeMedida() {
        this.$btnSalvarUnidade.on('click', (e) => {
            e.preventDefault();

            const nome = this.$novaUnidadeNome.val().trim();
            const simbolo = this.$novaUnidadeSimbolo.val().trim();

            if (!nome || !simbolo) {
                return Utils.showAlert(this.$modalNovaUnidadeMedidaErro, 'Preencha nome e símbolo da unidade.');
            }

            $.post('/unidadeMedida/storeAjax', { nome, simbolo }, (res) => {
                if (res.success && res.unidade) {
                    const nova = res.unidade;
                    const texto = `${nova.nome} (${nova.simbolo})`;
                    Utils.addNewSelectOption(this.$selectUnidade, nova.unidade_medida_id, texto);
                    this.modalNovaUnidade.hide();
                    this.$novaUnidadeNome.val('');
                    this.$novaUnidadeSimbolo.val('');
                } else {
                    Utils.showAlert(this.$modalNovaUnidadeMedidaErro, 'Erro ao salvar unidade de medida.');
                }
            }, 'json').fail(() => {
                Utils.showAlert(this.$modalNovaUnidadeMedidaErro, 'Erro ao salvar unidade de medida.');
            });
        });
    }
}

let produtoCreate;
$(() => produtoCreate = new ProdutoCreate());
