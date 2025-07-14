class BaseList {
    constructor({ entity, containerId, columns, defaultOrder = null, modalId = 'modal_confirm_delete' }) {
        this.entity = entity;
        this.columns = columns;
        this.$container = $(`#${containerId}`);
        this.$searchInput = this.$container.find('input[name="q"]');
        this.order = defaultOrder || columns[0].field;
        this.direction = 'desc';
        this.page = 1;
        this.idToDelete = null;
        this.modalId = modalId;

        this.listen();
        this.fetchData();
    }

    listen() {
        if (this.$searchInput.length) {
            this.$searchInput.on('input', Utils.debounce(() => {
                this.page = 1;
                this.fetchData();
            }));
        }

        this.$container.on('click', 'th.sortable', (e) => {
            const $th = $(e.currentTarget);
            const field = $th.data('field');

            if (this.order === field) {
                this.direction = this.direction === 'asc' ? 'desc' : 'asc';
            } else {
                this.order = field;
                this.direction = 'asc';
            }

            this.updateSortIcons();
            this.fetchData();
        });

        this.$container.on('click', '.nav-pagination a', (e) => {
            e.preventDefault();
            const $a = $(e.currentTarget);
            const page = parseInt($a.data('page'));
            if (!isNaN(page)) {
                this.page = page;
                this.fetchData();
            }
        });

        this.$container.on('click', '.btn-delete', (e) => {
            const $btn = $(e.currentTarget);
            this.idToDelete = $btn.data('id');
            this.deleteModal = new bootstrap.Modal(document.getElementById(this.modalId));
            this.deleteModal.show();
        });

        $('#btn_confirm_delete').on('click', () => {
            if (!this.idToDelete) return;
            $.ajax({
                url: `/${this.entity}/delete/${this.idToDelete}?ajax=1`,
                method: 'POST',
                success: () => {
                    this.idToDelete = null;
                    this.deleteModal.hide();
                    this.fetchData();
                },
                error: () => {
                    Utils.showToast('Erro ao excluir o registro');
                }
            });
        });
    }

    updateSortIcons() {
        this.$container.find('th.sortable').each((_, th) => {
            const $th = $(th);
            const field = $th.data('field');
            $th.removeClass('sorted-asc sorted-desc');
            if (field === this.order) {
                $th.addClass(`sorted-${this.direction}`);
            }
        });
    }

    fetchData() {
        $.get(`/${this.entity}`, {
            q: this.$searchInput.val(),
            page: this.page,
            order: this.order,
            direction: this.direction,
            ajax: 1
        }, (html) => {
            const $new = $(html);
            const selector = `#table-${this.entity}`;
            this.$container.find(selector).html($new.html());
            this.updateSortIcons();
        });
    }
}
