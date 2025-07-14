class CategoryForm {
    constructor() {
        this.$container = $('.container');
        this.$name = this.$container.find('#name');
        this.$icon = this.$container.find('#icon');
        this.$iconPreview = this.$container.find('#icon-preview');

        this.init();
    }

    init() {
        this.initUpdate();
        this.initIconPicker();
    }

    initUpdate() {
        if (!window.updateData) return;

        const c = window.updateData;
        this.$name.val(c.name);
    }

    initIconPicker() {
        $.getJSON('/icon/list', (icons) => {
            const $dropdownContainer = $('#dropdown-container');
            const $dropdown = $('#icon-dropdown');
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
                const iconClass = String($(e.currentTarget).data('class'));
                const iconName = String($(e.currentTarget).data('name'));
                $('#icon-preview').attr('class', iconClass + ' me-2');
                $('#icon-name').text(iconName.replace(/-/g, ' '));
                $('#icon').val(iconClass);

                // Fecha o dropdown
                $('#dropdown-container').removeClass('show');
                $('#icon-btn').attr('aria-expanded', 'false');
            });

            // Filtro
            $dropdownContainer.on('keyup', '#icon-search', Utils.debounce(function () {
                const search = $(this).val().toLowerCase();
                $dropdown.find('a.dropdown-item').each(function () {
                    const name = String($(this).data('name')).toLowerCase();
                    const method = name.includes(search) ? 'show' : 'hide';
                    $(this).closest('li')[method]();
                });
            }));

            // Se estiver editando
            if (window.updateData?.icon) {
                const icon = window.updateData.icon;
                $('#icon-preview').attr('class', icon + ' me-2');
                const name = icon.split(' ').pop().replace('fa-', '');
                $('#icon-name').text(name.replace(/-/g, ' '));
                $('#icon').val(icon);
            }
        });
    }
}

let categoryForm;
$(() => categoryForm = new CategoryForm());
