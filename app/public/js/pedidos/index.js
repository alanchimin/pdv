class Pedido {
    constructor() {
        this.$categoriaBtns = $('.categoria-item');
        this.$produtos = $('.produto-item');
        this.$modal = $('#modalProduto');
        this.$listaItens = $('#lista-itens');
        this.$btnAdicionar = $('#btn-adicionar');
        this.$btnFinalizar = $('#btn-finalizar-pedido');
        this.$btnConfirmarLimpeza = $('#btn-confirmar-limpeza');
        this.$mensagemErro = $('#mensagem-erro');
        this.$mensagemFinalizar = $('#mensagem-finalizar');
        this.$placeholder = $('#lista-placeholder');
        this.$buscaProduto = $('#busca-produto');

        this.listen();
        this.carregarTela();
    }

    listen() {
        this.$buscaProduto.on('input', Utils.debounce(() => this.carregarProdutos()));
        this.$categoriaBtns.on('click', this.handleCategoriaClick.bind(this));
        this.$produtos.on('click', this.abrirModalProduto.bind(this));
        $('input[name="tipo-desconto"]').on('change', this.toggleTipoDesconto.bind(this));
        this.$btnAdicionar.on('click', this.adicionarItem.bind(this));
        this.$btnFinalizar.on('click', this.finalizarPedido.bind(this));
        this.$btnConfirmarLimpeza.on('click', this.limparCarrinho.bind(this));
        this.$listaItens.on('click', '.btn-remover-item', this.handleClickBtnRemoverItem.bind(this));
    }

    handleCategoriaClick(e) {
        $('.categoria-item').removeClass('active');
        $(e.currentTarget).addClass('active');
        this.carregarProdutos();
    }

    abrirModalProduto(e) {
        const $el = $(e.currentTarget);
        $('#produto-id').val($el.data('id'));
        $('#produto-nome').val($el.data('nome'));
        $('#quantidade').val(1);
        $('#desconto-porcentagem').val(0);
        $('#desconto-reais').val(0.00);

        this.$btnAdicionar
            .data('valor', parseFloat($el.data('valor')))
            .data('unidade', $el.data('unidade'));

        this.$modal.modal('show');
    }

    toggleTipoDesconto() {
        const tipo = $('input[name="tipo-desconto"]:checked').val();
        $('#campo-desconto-percentual').toggleClass('d-none', tipo !== 'percentual');
        $('#campo-desconto-reais').toggleClass('d-none', tipo !== 'reais');
    }

    adicionarItem() {
        this.limparErro();
        const id = $('#produto-id').val();
        const nome = $('#produto-nome').val();
        const qtd = parseInt($('#quantidade').val());
        const valor = parseFloat(this.$btnAdicionar.data('valor'));
        const unidade = this.$btnAdicionar.data('unidade')
        const tipoDesconto = $('input[name="tipo-desconto"]:checked').val();

        if (!qtd || qtd <= 0) return this.mostrarErro('Informe uma quantidade válida maior que zero.');

        let desconto = 0;
        const total = qtd * valor;

        if (tipoDesconto === 'percentual') {
            const pct = parseFloat($('#desconto-porcentagem').val()) || 0;
            if (pct < 0 || pct > 100) return this.mostrarErro('O desconto percentual deve estar entre 0% e 100%.');
            desconto = total * (pct / 100);
        } else {
            desconto = parseFloat($('#desconto-reais').val()) || 0;
        }

        if (desconto > total) return this.mostrarErro('O desconto não pode ser maior que o valor total do produto.');

        const final = total - desconto;

        const html = `
            <li class="list-group-item d-flex justify-content-between align-items-center"
                data-produto-id="${id}"
                data-nome="${nome}"
                data-quantidade="${qtd}"
                data-valor-unitario="${valor}"
                data-desconto="${desconto}"
                data-unidade="${unidade}">
                
                <div>
                    <strong>${nome}</strong><br>
                    <small>${qtd} ${unidade} x R$ ${valor.toFixed(2)} - Desc: R$ ${desconto.toFixed(2)}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill">R$ ${final.toFixed(2)}</span>
                    <button class="btn btn-sm btn-outline-danger btn-remover-item" title="Remover item">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </li>
        `;

        this.$listaItens.append(html);
        this.$modal.modal('hide');
        this.atualizarTotais();
        this.salvarListaEmLocalStorage();

        this.$listaItens.closest('.border').animate({ scrollTop: this.$listaItens.prop('scrollHeight') }, 300);
    }

    handleClickBtnRemoverItem(e) {
        $(e.currentTarget).closest('li').remove();
        this.atualizarTotais();
        this.salvarListaEmLocalStorage();
    }

    finalizarPedido() {
        const itens = this.obterItens();
        if (itens.length === 0) return this.mostrarErroFinalizar('Nenhum item no pedido.');

        $.post('/pedido/store', { itens: JSON.stringify(itens) }, (res) => {
            if (res.success) {
                $.getJSON(`/pedido/getPdfLink/${res.pedido_id}`, (pdfData) => {
                    if (pdfData.url) {
                        window.open(pdfData.url, '_blank');
                        this.limparCarrinho();
                    } else {
                        this.mostrarErroFinalizar('Erro ao gerar o PDF.');
                    }
                });
            } else {
                this.mostrarErroFinalizar('Erro ao salvar o pedido.');
            }
        }, 'json');
    }

    limparCarrinho() {
        this.$listaItens.empty();
        localStorage.removeItem('itensPedido');
        this.atualizarTotais();
    }

    mostrarErro(msg) {
        this.$mensagemErro.text(msg).removeClass('d-none');
    }

    limparErro() {
        this.$mensagemErro.text('').addClass('d-none');
    }

    mostrarErroFinalizar(msg) {
        this.$mensagemFinalizar.text(msg).removeClass('d-none');
        setTimeout(() => this.$mensagemFinalizar.text('').addClass('d-none'), 4000);
    }

    salvarListaEmLocalStorage() {
        const itens = this.obterItens();
        localStorage.setItem('itensPedido', JSON.stringify(itens));
    }

    obterItens() {
        const itens = [];
        this.$listaItens.find('li').each(function () {
            const $el = $(this);
            itens.push({
                produtoId: $el.data('produto-id'),
                nome: $el.data('nome'),
                quantidade: parseInt($el.data('quantidade')),
                valorUnitario: parseFloat($el.data('valor-unitario')),
                desconto: parseFloat($el.data('desconto')),
                unidade: $el.data('unidade'),
            });
        });
        return itens;
    }

    carregarListaDoLocalStorage() {
        const itens = JSON.parse(localStorage.getItem('itensPedido') || '[]');
        this.$listaItens.empty();

        itens.forEach(item => {
            const total = item.valorUnitario * item.quantidade;
            const final = total - item.desconto;

            const html = `
                <li class="list-group-item d-flex justify-content-between align-items-center"
                    data-produto-id="${item.produtoId}"
                    data-nome="${item.nome}"
                    data-quantidade="${item.quantidade}"
                    data-valor-unitario="${item.valorUnitario}"
                    data-desconto="${item.desconto}"
                    data-unidade="${item.unidade}">
                    
                    <div>
                        <strong>${item.nome}</strong><br>
                        <small>${item.quantidade} ${item.unidade} x R$ ${item.valorUnitario.toFixed(2)} - Desc: R$ ${item.desconto.toFixed(2)}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill">R$ ${final.toFixed(2)}</span>
                        <button class="btn btn-sm btn-outline-danger btn-remover-item" title="Remover item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </li>
            `;
            this.$listaItens.append(html);
        });
    }

    atualizarTotais() {
        let subtotal = 0;
        let descontos = 0;

        this.$listaItens.find('li').each(function () {
            const $el = $(this);
            const qtd = parseInt($el.data('quantidade'));
            const valor = parseFloat($el.data('valor-unitario'));
            const desc = parseFloat($el.data('desconto'));
            subtotal += qtd * valor;
            descontos += desc;
        });

        const totalFinal = subtotal - descontos;

        $('#pedido-subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
        $('#pedido-descontos').text(`R$ ${descontos.toFixed(2).replace('.', ',')}`);
        $('#pedido-total').text(`R$ ${totalFinal.toFixed(2).replace('.', ',')}`);

        this.$placeholder.toggle(this.$listaItens.find('li').length === 0);
    }

    carregarTela() {
        this.carregarListaDoLocalStorage();
        this.atualizarTotais();
        this.carregarProdutos();
    }

    carregarProdutos(pagina = 1) {
        const termo = this.$buscaProduto.val();
        const categoriaId = $('.categoria-item.active').data('categoria-id');

        $('#grid-produtos').html('<div class="text-center w-100 my-5"><div class="spinner-border text-primary" role="status"></div></div>');

        $.get('/pedido/grid', {
            q: termo,
            categoria_id: categoriaId,
            pagina: pagina,
            ajax: 1
        }, (html) => {
            $('#produtos-grid-content').replaceWith(html);
            this.$produtos = $('.produto-item'); // rebind
            this.$produtos.on('click', this.abrirModalProduto.bind(this));
            this.bindPaginacao();
        });
    }

    bindPaginacao() {
        $('#produtos-grid-content .pagination a.page-link').on('click', (e) => {
            e.preventDefault();
            const pagina = $(e.currentTarget).data('pagina');
            this.carregarProdutos(pagina);
        });
    }
}

let pedido;
$(() => pedido = new Pedido());
