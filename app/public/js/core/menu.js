class Menu {
    constructor() {
        this.$sidebar = $('.sidebar');
        this.$btnToggleMenu = $('#btn_toggle_menu');
        this.$btnToggleMenuMobile = $('#btn_toggle_menu_mobile');
        this.$menuOverlay = $('.menu-overlay');

        this.listen();
    }

    listen() {
        this.$btnToggleMenu.on('click', this.toggleMenu.bind(this));
        this.$btnToggleMenuMobile.on('click', this.toggleMenu.bind(this));
        this.$menuOverlay.on('click', this.closeMenu.bind(this));
        $(window).on('resize', this.handleWindowResize.bind(this));
    }

    toggleMenu() {
        const isOpen = this.$sidebar.hasClass('open');
        const method = isOpen ? 'closeMenu' : 'openMenu';
        this[method]();
    }

    openMenu() {
        this.$sidebar.addClass('open');
        this.$menuOverlay.addClass('show');

        // Esconde o botão flutuante apenas em telas pequenas
        if (window.innerWidth < 576) {
            this.$btnToggleMenuMobile.hide();
        }
    }

    closeMenu() {
        this.$sidebar.removeClass('open');
        this.$menuOverlay.removeClass('show');

        // Mostra o botão flutuante apenas em telas pequenas
        if (window.innerWidth < 576) {
            this.$btnToggleMenuMobile.show();
        }
    }

    handleWindowResize() {
        if (window.innerWidth >= 576) {
            this.$btnToggleMenuMobile.hide();
        } else if (!this.$sidebar.hasClass('open')) {
            this.$btnToggleMenuMobile.show();
        }
    }
}

let menu;
$(() => menu = new Menu());
