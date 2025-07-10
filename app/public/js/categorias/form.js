class CategoriaForm {
    constructor() {
        this.$ctrl = $('.container');
        this.$nome = this.$ctrl.find('#nome');
        this.$icone = this.$ctrl.find('#icone');
        this.$iconePreview = this.$ctrl.find('#icone-preview');

        this.init();
    }

    init() {
        this.initUpdate();
        this.initIconPicker();
    }

    initUpdate() {
        if (!window.updateData) return;

        const c = window.updateData;
        this.$nome.val(c.nome);
    }

    initIconPicker() {
        $.getJSON('/icone/list', (icons) => {
            const $dropdownContainer = $('#dropdown-container');
            const $dropdown = $('#icone-dropdown');
            $dropdown.empty();

            // Itens
            icons.forEach(icon => {
                const $item = $(`
                    <li>
                        <a href="#" class="dropdown-item d-flex align-items-center" data-class="${String(icon.class)}" data-name="${String(icon.name)}">
                            <i class="${icon.class} me-2"></i> ${icon.name.replace(/-/g, ' ')}
                        </a>
                    </li>
                `);
                $dropdown.append($item);
            });

            // Clique no item
            $dropdown.on('click', 'a', (e) => {
                e.preventDefault();
                const iconeClass = String($(e.currentTarget).data('class'));
                const iconeName = String($(e.currentTarget).data('name'));
                $('#icone-preview').attr('class', iconeClass + ' me-2');
                $('#icone-nome').text(iconeName.replace(/-/g, ' '));
                $('#icone').val(iconeClass);

                // Fecha o dropdown
                $('#dropdown-container').removeClass('show');
                $('#icone-btn').attr('aria-expanded', 'false');
            });

            // Filtro
            $dropdownContainer.on('keyup', '#icone-search', Utils.debounce(function () {
                const search = $(this).val().toLowerCase();
                $dropdown.find('a.dropdown-item').each(function () {
                    const nome = String($(this).data('name')).toLowerCase();
                    const method = nome.includes(search) ? 'show' : 'hide';
                    $(this).closest('li')[method]();
                });
            }));

            // Se estiver editando
            if (window.updateData?.icone) {
                const classe = window.updateData.icone;
                $('#icone-preview').attr('class', classe + ' me-2');
                const name = classe.split(' ').pop().replace('fa-', '');
                $('#icone-nome').text(name.replace(/-/g, ' '));
                $('#icone').val(classe);
            }
        });
    }
}

let categoriaForm;
$(() => categoriaForm = new CategoriaForm());
