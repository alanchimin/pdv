class ProductForm {
    constructor() {
        this.$container = $('.container');
        this.$name = this.$container.find('#name');
        this.$unitPrice = this.$container.find('#unit_price');
        this.$selects = this.$container.find('.selectpicker');
        this.$imageUrl = this.$container.find('#image_url');
        this.$imageFile = this.$container.find('#image_file');
        this.$imagePreview = this.$container.find('#image_preview');
        this.$radioUrl = this.$container.find('#radio_url');
        this.$radioUpload = this.$container.find('#radio_upload');
        this.$discount = this.$container.find('#discount');

        this.$selectCategory = this.$container.find('#category_id');
        this.$categoryName = this.$container.find('#category_name');
        this.$iconModal = this.$container.find('#icon_modal');
        this.$iconDropdownModal = this.$container.find('#icon_dropdown_modal');
        this.$iconPreviewModal = this.$container.find('#icon_preview_modal');
        this.$iconNameModal = this.$container.find('#icon_name_modal');
        this.$btnIconModal = this.$container.find('#btn_icon_modal');
        this.$iconSearchModal = this.$container.find('#icon_search_modal');
        this.$dropdownContainerModal = this.$container.find('#dropdown_container_modal');
        this.$btnSaveCategory = this.$container.find('#btn_save_category');
        this.modalNewCategory = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal_new_category'));

        this.$selectUnit = this.$container.find('#unit_id');
        this.$unitName = this.$container.find('#unit_name');
        this.$unitSymbol = this.$container.find('#unit_symbol');
        this.$btnSaveUnit = this.$container.find('#btn_save_unit');
        this.modalNewUnit = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal_new_unit'));

        this.$btnSave = this.$container.find('#btn_save');

        this.currentUrl = '';
        this.currentFile = '';

        this.init();
    }

    init() {
        this.initSelects();
        this.initRadioToggle();
        this.initImagePreview();
        this.initUploadPreview();
        this.initSaveCategory();
        this.initSaveUnit();
        this.initIconPickerModal();
        this.initUpdate();
        this.initSubmit();
    }

    initSelects() {
        if (this.$selects.length) {
            this.$selects.selectpicker();
        }
    }

    initRadioToggle() {
        this.$radioUrl.on('change', () => {
            if (this.$radioUrl.is(':checked')) {
                this.$imageUrl.show();
                this.$imageFile.hide();

                if (this.currentUrl) {
                    this.$imagePreview.attr('src', this.currentUrl).show();
                } else {
                    this.$imagePreview.hide();
                }
            }
        });

        this.$radioUpload.on('change', () => {
            if (this.$radioUpload.is(':checked')) {
                this.$imageFile.show();
                this.$imageUrl.hide();

                if (this.currentFile) {
                    this.$imagePreview.attr('src', this.currentFile).show();
                } else {
                    this.$imagePreview.hide();
                }
            }
        });
    }

    initImagePreview() {
        this.$imageUrl.on('input', () => {
            this.currentUrl = this.$imageUrl.val();
            if (this.$radioUrl.is(':checked') && this.currentUrl) {
                this.$imagePreview.attr('src', this.currentUrl).show();
            } else if (this.$radioUrl.is(':checked')) {
                this.$imagePreview.hide();
            }
        });
    }

    initUploadPreview() {
        this.$imageFile.on('change', (e) => {
            const file = e.target.files[0];
            if (!file) {
                this.currentFile = '';
                if (this.$radioUpload.is(':checked')) this.$imagePreview.hide();
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.currentFile = e.target.result;
                if (this.$radioUpload.is(':checked')) {
                    this.$imagePreview.attr('src', this.currentFile).show();
                }
            };
            reader.readAsDataURL(file);
        });
    }

    initSaveCategory() {
        this.$btnSaveCategory.on('click', (e) => {
            e.preventDefault();
            const name = this.$categoryName.val().trim();
            const icon = this.$iconModal.val().trim();

            if (!name || !icon) {
                return Utils.showToast('Informe nome e ícone.');
            }

            $.post('/category/storeAjax', { name, icon }, (res) => {
                if (res.success && res.category) {
                    Utils.addNewSelectOption(this.$selectCategory, res.category.category_id, res.category.name);
                    this.modalNewCategory.hide();
                    this.$categoryName.val('');
                } else {
                    Utils.showToast('Erro ao salvar categoria.');
                }
            }, 'json').fail(() => {
                Utils.showToast('Erro ao salvar categoria.');
            });
        });
    }

    initSaveUnit() {
        this.$btnSaveUnit.on('click', (e) => {
            e.preventDefault();

            const name = this.$unitName.val().trim();
            const symbol = this.$unitSymbol.val().trim();

            if (!name || !symbol) {
                return Utils.showToast('Preencha nome e símbolo da unidade.');
            }

            $.post('/unit/storeAjax', { name, symbol }, (res) => {
                if (res.success && res.unit) {
                    const text = `${res.unit.name} (${res.unit.symbol})`;
                    Utils.addNewSelectOption(this.$selectUnit, res.unit.unit_id, text);
                    this.modalNewUnit.hide();
                    this.$unitName.val('');
                    this.$unitSymbol.val('');
                } else {
                    Utils.showToast('Erro ao salvar unidade de medida.');
                }
            }, 'json').fail(() => {
                Utils.showToast('Erro ao salvar unidade de medida.');
            });
        });
    }

    initIconPickerModal() {
        $.getJSON('/icon/list', (icons) => {
            const $dropdown = this.$iconDropdownModal;
            $dropdown.empty();

            // Listagem
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
                const name = String($(e.currentTarget).data('name'));

                this.$iconPreviewModal.attr('class', iconClass + ' me-2');
                this.$iconNameModal.text(name.replace(/-/g, ' '));
                this.$iconModal.val(iconClass);

                // Fecha o dropdown
                this.$dropdownContainerModal.removeClass('show');
                this.$btnIconModal.attr('aria-expanded', 'false');
            });

            // Filtro
            this.$iconSearchModal.on('input', Utils.debounce(function () {
                const search = $(this).val().toLowerCase();
                $dropdown.find('a.dropdown-item').each(function () {
                    const name = $(this).data('name').toLowerCase();
                    const show = name.includes(search);
                    $(this).closest('li').toggle(show);
                });
            }));
        });
    }

    initUpdate() {
        if (!window.updateData) return;

        const p = window.updateData;

        this.$name.val(p.name);
        this.$unitPrice.val(p.unit_price);
        Utils.setSelectOption(this.$selectUnit, p.unit_id);
        Utils.setSelectOption(this.$selectCategory, p.category_id);

        if (p.image_type === 'url') {
            this.$radioUrl.prop('checked', true).trigger('change');
            this.$imageUrl.val(p.image).trigger('input');
        } else if (p.image_type === 'upload') {
            this.$radioUpload.prop('checked', true).trigger('change');
            const path = `/upload/${p.image}`;
            this.currentFile = path;
            this.$imagePreview.attr('src', path).show();
        }

        this.$discount.val(p.discount || 0);
    }

    initSubmit() {
        this.$btnSave.on('click', (e) => {
            if (!this.$name.val())
                return;

            if (!this.$selectCategory.val()) {
                Utils.showToast('Selecione uma categoria.');
                e.preventDefault();
                return;
            }

            if (!this.$selectUnit.val()) {
                Utils.showToast('Selecione uma unidade de medida.');
                e.preventDefault();
                return;
            }
        });
    }
}

let productForm;
$(() => productForm = new ProductForm());
