class UnidadeForm {
    constructor() {
        this.$ctrl = $('.container');
        this.$nome = this.$ctrl.find('#nome');
        this.$simbolo = this.$ctrl.find('#simbolo');

        this.init();
    }

    init() {
        this.initUpdate();
    }

    initUpdate() {
        if (!window.updateData) return;

        const u = window.updateData;
        this.$nome.val(u.nome);
        this.$simbolo.val(u.simbolo);
    }
}

let unidadeForm;
$(() => unidadeForm = new UnidadeForm());
