class Menu {
    constructor() {
        this.$sidebar = $('.sidebar');
        this.$btnToggle = $('#btnToggleMenu');
        this.$btnToggleMobile = $('#btnToggleMenuMobile');
        this.$overlay = $('#menuOverlay');

        this.listen();
    }

    listen() {
        this.$btnToggle.on('click', this.toggleMenu.bind(this));
        this.$btnToggleMobile.on('click', this.toggleMenu.bind(this));
        this.$overlay.on('click', this.closeMenu.bind(this));
        $(window).on('resize', this.handleWindowResize.bind(this));
    }

    toggleMenu() {
        const isOpen = this.$sidebar.hasClass('open');
        const method = isOpen ? 'closeMenu' : 'openMenu';
        this[method]();
    }

    openMenu() {
        this.$sidebar.addClass('open');
        this.$overlay.addClass('show');

        // Esconde o botão flutuante apenas em telas pequenas
        if (window.innerWidth < 576) {
            this.$btnToggleMobile.hide();
        }
    }

    closeMenu() {
        this.$sidebar.removeClass('open');
        this.$overlay.removeClass('show');

        // Mostra o botão flutuante apenas em telas pequenas
        if (window.innerWidth < 576) {
            this.$btnToggleMobile.show();
        }
    }

    handleWindowResize() {
        if (window.innerWidth >= 576) {
            this.$btnToggleMobile.hide();
        } else if (!this.$sidebar.hasClass('open')) {
            this.$btnToggleMobile.show();
        }
    }
}

let menu;
$(() => menu = new Menu());
