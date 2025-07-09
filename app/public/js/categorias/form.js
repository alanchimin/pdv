class CategoriaForm {
    constructor() {
        this.$ctrl = $('.container');
        this.$nome = this.$ctrl.find('#nome');

        this.init();
    }

    init() {
        this.initUpdate();
    }

    initUpdate() {
        if (!window.updateData) return;

        const c = window.updateData;
        this.$nome.val(c.nome);
    }
}

let categoriaForm;
$(() => categoriaForm = new CategoriaForm());
