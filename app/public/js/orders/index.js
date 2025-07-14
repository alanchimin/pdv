class Order {
    constructor() {
        this.$categories = $('.category-item');
        this.$products = $('.product-item');
        this.$modalProduct = $('#modal_product');
        this.$cartItems = $('#cart_items');
        this.$btnAddProduct = $('#btn_add_product');
        this.$btnCheckout = $('#btn_checkout');
        this.$btnConfirmClearCart = $('#btn_confirm_clear_cart');
        this.$emptyCart = $('#empty_cart');
        this.$search = $('#search');
        this.$productId = $('#product_id');
        this.$productName = $('#product_name');
        this.$amount = $('#amount');
        this.$unit = $('#unit');
        this.$discountContainer = $('#discount_container');
        this.$discount = $('#discount');
        this.$total = $('#total');
        this.$btnConfirmOrder = $('#btn_confirm_order');
        this.$selectPaymentMethod = $('#payment_method_id');
        this.$btnCart = $('#btn_floating_cart');
        this.$categoriesProductsContainer = $('#categories_products_container');
        this.$cartContainer = $('#cart_container');
        this.$btnBackFromCart = $('#btn_back_from_cart');
        this.$modalConfirmOrder = $('#modal_confirm_order');
        this.$orderSubtotal = $('#order_subtotal');
        this.$orderDiscounts = $('#order_discounts');
        this.$orderTotal = $('#order_total');

        this.listen();
        this.loadScreen();
    }

    listen() {
        this.$search.on('input', Utils.debounce(() => this.loadProducts()));
        this.$categories.on('click', this.handleCategoryClick.bind(this));
        this.$products.on('click', this.openModalProduct.bind(this));
        this.$btnAddProduct.on('click', this.handleClickBtnAddProduct.bind(this));
        this.$btnCheckout.on('click', this.checkout.bind(this));
        this.$btnConfirmClearCart.on('click', this.clearCart.bind(this));
        this.$cartItems.on('click', '.btn-remove-item', this.handleClickBtnRemoveItem.bind(this));
        this.$amount.on('input', this.updateModalProductTotal.bind(this));
        this.$btnConfirmOrder.on('click', this.handleClickBtnConfirmOrder.bind(this));
        this.$btnCart.on('click', this.handleClickBtnCart.bind(this));
        this.$btnBackFromCart.on('click', this.handleClickBtnBackFromCart.bind(this));
    }

    handleCategoryClick(e) {
        this.$categories.removeClass('active');
        $(e.currentTarget).addClass('active');
        this.loadProducts();
    }

    openModalProduct(e) {
        const $el = $(e.currentTarget);
        this.$productId.val($el.data('id'));
        this.$productName.val($el.data('name'));

        const unitPrice = parseFloat($el.data('unit-price'));
        const discount = parseFloat($el.data('discount')) || 0;
        const amount = 1;

        this.$amount.val(amount);
        this.$unit.text($el.data('unit'));

        const total = amount * unitPrice * (1 - discount / 100);

        this.$btnAddProduct
            .data('unit-price', unitPrice)
            .data('unit', $el.data('unit'))
            .data('discount', discount);

        if (discount > 0) {
            this.$discountContainer.removeClass('d-none');
            this.$discount.val((unitPrice * discount / 100).toFixed(2).replace('.', ','));
        } else {
            this.$discountContainer.addClass('d-none');
            this.$discount.val('');
        }

        this.$total.val(total.toFixed(2).replace('.', ','));

        this.$modalProduct.modal('show');

        this.updateModalProductTotal();
    }

    updateModalProductTotal() {
        const unitPrice = parseFloat(this.$btnAddProduct.data('unit-price'));
        const discount = parseFloat(this.$btnAddProduct.data('discount')) || 0;
        const amount = parseInt(this.$amount.val()) || 1;

        if (discount > 0) {
            const totalDiscount = amount * unitPrice * (discount / 100);
            this.$discountContainer.removeClass('d-none');
            this.$discount.val(totalDiscount.toFixed(2).replace('.', ','));
        } else {
            this.$discountContainer.addClass('d-none');
            this.$discount.val('');
        }

        const total = unitPrice * amount * (1 - discount / 100);
        this.$total.val(total.toFixed(2).replace('.', ','));
    }

    handleClickBtnAddProduct() {
        const id = this.$productId.val();
        const name = this.$productName.val();
        const amount = parseFloat(this.$amount.val());
        const unitPrice = parseFloat(this.$btnAddProduct.data('unit-price'));
        const unit = this.$btnAddProduct.data('unit');
        const discount = parseFloat(this.$btnAddProduct.data('discount')) || 0;

        if (!amount || amount <= 0) {
            Utils.showToast('Informe uma quantidade válida maior que zero.');
            return;
        }

        const totalDiscount = (unitPrice * (discount / 100)) * amount;
        const total = amount * unitPrice - totalDiscount;
        const discountStr = totalDiscount > 0 ? ` - Desc: R$ ${totalDiscount.toFixed(2)}` : '';

        const html = `
            <li class="list-group-item d-flex justify-content-between align-items-center"
                data-product-id="${id}"
                data-name="${name}"
                data-amount="${amount}"
                data-unit-price="${unitPrice}"
                data-discount="${totalDiscount}"
                data-unit="${unit}">
                
                <div>
                    <strong>${name}</strong><br>
                    <small>${amount} ${unit} x R$ ${unitPrice.toFixed(2)}${discountStr}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill">R$ ${total.toFixed(2)}</span>
                    <button class="btn btn-sm btn-outline-danger btn-remove-item" title="Remover item">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </li>
        `;

        this.$cartItems.append(html);
        this.$modalProduct.modal('hide');
        this.updateCartTotal();
        this.saveCartToLocalStorage();

        this.$cartItems.closest('.border').animate({ scrollTop: this.$cartItems.prop('scrollHeight') }, 300);
    }

    handleClickBtnRemoveItem(e) {
        $(e.currentTarget).closest('li').remove();
        this.updateCartTotal();
        this.saveCartToLocalStorage();
    }

    checkout() {
        const items = this.getItems();
        if (items.length === 0) {
            Utils.showToast('Nenhum item no pedido.');
            return;
        }

        const $tbody = this.$modalConfirmOrder.find('table tbody');
        $tbody.empty();

        // Renderiza até os 3 primeiros itens
        items.slice(0, 3).forEach(item => {
            $tbody.append(`
                <tr>
                    <td colspan="2">${item.name} - ${item.amount} ${item.unit}</td>
                </tr>
            `);
        });

        // Se houver mais de 3 itens, mostra o "+N itens"
        if (items.length > 3) {
            const remaining = items.length - 3;
            const text = remaining === 1 ? 'item' : 'itens';
            $tbody.append(`<tr><td colspan="2">+${remaining} ${text}</td></tr>`);
        }

        // Totalizadores
        $tbody.append(`
            <tr>
                <td class="text-start"><b>Subtotal</b></td>
                <td class="text-end">${this.$orderSubtotal.text()}</td>
            </tr>
            <tr>
                <td class="text-start"><b>Descontos</b></td>
                <td class="text-end">${this.$orderDiscounts.text()}</td>
            </tr>
            <tr>
                <td class="text-start"><b>Total</b></td>
                <td class="text-end">${this.$orderTotal.text()}</td>
            </tr>
        `);

        this.$selectPaymentMethod.val('');

        this.$modalConfirmOrder.modal('show');
    }

    clearCart() {
        this.$cartItems.empty();
        localStorage.removeItem('pdv_order');
        this.updateCartTotal();
    }

    handleClickBtnConfirmOrder() {
        const paymentMethodId = this.$selectPaymentMethod.val();
        const items = this.getItems();

        if (!paymentMethodId) {
            Utils.showToast('Selecione a forma de pagamento.');
            return;
        }

        $.post('/order/store', {
            payment_method_id: paymentMethodId,
            items: JSON.stringify(items)
        }, (res) => {
            if (res.success) {
                $.getJSON(`/order/getPdfLink/${res.order_id}`, (pdfData) => {
                    if (pdfData.url) {
                        window.open(pdfData.url, '_blank');
                        this.$modalConfirmOrder.modal('hide');
                        this.clearCart();
                    } else {
                        Utils.showToast('Erro ao gerar o PDF.');
                    }
                });
            } else {
                Utils.showToast('Erro ao salvar o pedido.');
            }
        }, 'json');
    }

    handleClickBtnCart() {
        this.cart = !this.cart;
        if (this.cart) {
            this.$categoriesProductsContainer.addClass('d-none');
            this.$cartContainer.removeClass('d-none');
        } else {
            this.$cartContainer.addClass('d-none');
            this.$categoriesProductsContainer.removeClass('d-none');
        }
    }

    handleClickBtnBackFromCart() {
        this.$cartContainer.addClass('d-none');
        this.$categoriesProductsContainer.removeClass('d-none');
    }

    saveCartToLocalStorage() {
        const items = this.getItems();
        localStorage.setItem('pdv_order', JSON.stringify(items));
    }

    getItems() {
        const items = [];
        this.$cartItems.find('li').each(function () {
            const $el = $(this);
            items.push({
                productId: $el.data('product-id'),
                name: $el.data('name'),
                amount: parseInt($el.data('amount')),
                unitPrice: parseFloat($el.data('unit-price')),
                discount: parseFloat($el.data('discount')),
                unit: $el.data('unit'),
            });
        });
        return items;
    }

    loadCartFromLocalStorage() {
        const items = JSON.parse(localStorage.getItem('pdv_order') || '[]');
        this.$cartItems.empty();

        items.forEach(item => {
            const total = item.unitPrice * item.amount;
            const final = total - item.discount;
            const discountStr = item.discount > 0 ? ` - Desc: R$ ${item.discount.toFixed(2)}` : '';

            const html = `
                <li class="list-group-item d-flex justify-content-between align-items-center"
                    data-product-id="${item.productId}"
                    data-name="${item.name}"
                    data-amount="${item.amount}"
                    data-unit-price="${item.unitPrice}"
                    data-discount="${item.discount}"
                    data-unit="${item.unit}">
                    
                    <div>
                        <strong>${item.name}</strong><br>
                        <small>${item.amount} ${item.unit} x R$ ${item.unitPrice.toFixed(2)}${discountStr}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill">R$ ${final.toFixed(2)}</span>
                        <button class="btn btn-sm btn-outline-danger btn-remove-item" title="Remover item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </li>
            `;
            this.$cartItems.append(html);
        });
    }

    updateCartTotal() {
        let subtotal = 0;
        let discounts = 0;

        this.$cartItems.find('li').each(function () {
            const $el = $(this);
            const amount = parseInt($el.data('amount'));
            const unitPrice = parseFloat($el.data('unit-price'));
            const discount = parseFloat($el.data('discount'));
            subtotal += amount * unitPrice;
            discounts += discount;
        });

        const total = subtotal - discounts;

        this.$orderSubtotal.text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
        this.$orderDiscounts.text(`R$ ${discounts.toFixed(2).replace('.', ',')}`);
        this.$orderTotal.text(`R$ ${total.toFixed(2).replace('.', ',')}`);

        this.$emptyCart.toggle(this.$cartItems.find('li').length === 0);
    }

    loadScreen() {
        this.loadCartFromLocalStorage();
        this.updateCartTotal();
        this.loadProducts();
    }

    loadProducts(page = 1) {
        const search = this.$search.val();
        const categoryId = $('.category-item.active').data('category-id');

        $('#products_grid').html('<div class="text-center w-100 my-5"><div class="spinner-border text-primary" role="status"></div></div>');

        $.get('/order/grid', {
            q: search,
            category_id: categoryId,
            page: page,
            ajax: 1
        }, (html) => {
            $('#products_grid').replaceWith(html);
            this.$products = $('.product-item'); // rebind
            this.$products.on('click', this.openModalProduct.bind(this));
            this.bindPagination();
        });
    }

    bindPagination() {
        $('#products_grid .pagination a.page-link').on('click', (e) => {
            e.preventDefault();
            const page = $(e.currentTarget).data('page');
            this.loadProducts(page);
        });
    }
}

let order;
$(() => order = new Order());
