class UnitForm {
    constructor() {
        this.$container = $('.container');
        this.$name = this.$container.find('#name');
        this.$symbol = this.$container.find('#symbol');

        this.init();
    }

    init() {
        this.initUpdate();
    }

    initUpdate() {
        if (!window.updateData) return;

        const u = window.updateData;
        this.$name.val(u.name);
        this.$symbol.val(u.symbol);
    }
}

let unitForm;
$(() => unitForm = new UnitForm());
