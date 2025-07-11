class Pedido {
    constructor() {
        this.$categoriaBtns = $('.categoria-item');
        this.$produtos = $('.produto-item');
        this.$modal = $('#modalProduto');
        this.$listaItens = $('#lista-itens');
        this.$btnAdicionar = $('#btn-adicionar');
        this.$btnFinalizar = $('#btn-finalizar-pedido');
        this.$btnConfirmarLimpeza = $('#btn-confirmar-limpeza');
        this.$mensagemFinalizar = $('#mensagem-finalizar');
        this.$placeholder = $('#lista-placeholder');
        this.$buscaProduto = $('#busca-produto');
        this.$produtoId = $('#produto-id');
        this.$produtoNome = $('#produto-nome');
        this.$quantidade = $('#quantidade');
        this.$unidade = $('#unidade-label');
        this.$descontoContainer = $('#desconto-container');
        this.$descontoUnitario = $('#desconto-unitario');
        this.$descontoTotal = $('#desconto-total');
        this.$btnConfirmarPedido = $('#btn-confirmar-pedido');
        this.$selectFormaPagamento = $('#forma_pagamento_id');
        this.$btnCarrinho = $('#btn-carrinho');
        this.$categoriasProdutosContainer = $('#categorias-produtos-container');
        this.$carrinhoContainer = $('#carrinho-container');
        this.$carrinhoBtnVoltar = $('#carrinho-btn-voltar');

        this.listen();
        this.carregarTela();
    }

    listen() {
        this.$buscaProduto.on('input', Utils.debounce(() => this.carregarProdutos()));
        this.$categoriaBtns.on('click', this.handleCategoriaClick.bind(this));
        this.$produtos.on('click', this.abrirModalProduto.bind(this));
        this.$btnAdicionar.on('click', this.adicionarItem.bind(this));
        this.$btnFinalizar.on('click', this.finalizarPedido.bind(this));
        this.$btnConfirmarLimpeza.on('click', this.limparCarrinho.bind(this));
        this.$listaItens.on('click', '.btn-remover-item', this.handleClickBtnRemoverItem.bind(this));
        this.$quantidade.on('input', this.atualizarResumoPreco.bind(this));
        this.$btnConfirmarPedido.on('click', this.handleClickBtnConfirmarPedido.bind(this));
        this.$btnCarrinho.on('click', this.handleClickBtnCarrinho.bind(this));
        this.$carrinhoBtnVoltar.on('click', this.handleClickCarrinhoBtnVoltar.bind(this));
    }

    handleCategoriaClick(e) {
        $('.categoria-item').removeClass('active');
        $(e.currentTarget).addClass('active');
        this.carregarProdutos();
    }

    abrirModalProduto(e) {
        const $el = $(e.currentTarget);
        this.$produtoId.val($el.data('id'));
        this.$produtoNome.val($el.data('nome'));

        const valor = parseFloat($el.data('valor'));
        const descontoPercentual = parseFloat($el.data('desconto')) || 0;
        const qtd = 1;

        this.$quantidade.val(qtd);
        this.$unidade.text($el.data('unidade'));

        const valorComDescontoUnitario = valor * (1 - descontoPercentual / 100);
        const totalComDesconto = valorComDescontoUnitario * qtd;

        this.$btnAdicionar
            .data('valor', valor)
            .data('unidade', $el.data('unidade'))
            .data('desconto', descontoPercentual);

        if (descontoPercentual > 0) {
            $('#desconto-container').removeClass('d-none');
            $('#desconto').val((valor * descontoPercentual / 100).toFixed(2).replace('.', ','));
        } else {
            $('#desconto-container').addClass('d-none');
            $('#desconto').val('');
        }

        $('#total').val(totalComDesconto.toFixed(2).replace('.', ','));

        this.$modal.modal('show');

        this.atualizarResumoPreco();
    }

    atualizarResumoPreco() {
        const valor = parseFloat(this.$btnAdicionar.data('valor'));
        const descontoPercentual = parseFloat(this.$btnAdicionar.data('desconto')) || 0;
        const qtd = parseInt(this.$quantidade.val()) || 1;

        if (descontoPercentual > 0) {
            const descontoTotal = (valor * (descontoPercentual / 100)) * qtd;
            $('#desconto-container').removeClass('d-none');
            $('#desconto').val(descontoTotal.toFixed(2).replace('.', ','));
        } else {
            $('#desconto-container').addClass('d-none');
            $('#desconto').val('');
        }

        const total = valor * qtd * (1 - descontoPercentual / 100);
        $('#total').val(total.toFixed(2).replace('.', ','));
    }

    adicionarItem() {
        const id = $('#produto-id').val();
        const nome = $('#produto-nome').val();
        const qtd = parseInt(this.$quantidade.val());
        const valor = parseFloat(this.$btnAdicionar.data('valor'));
        const unidade = this.$btnAdicionar.data('unidade');
        const descontoPercentual = parseFloat(this.$btnAdicionar.data('desconto')) || 0;

        if (!qtd || qtd <= 0) {
            Utils.showToast('Informe uma quantidade válida maior que zero.');
            return;
        }

        const descontoTotal = (valor * (descontoPercentual / 100)) * qtd;
        const total = qtd * valor - descontoTotal;
        const descontoStr = descontoTotal > 0 ? ` - Desc: R$ ${descontoTotal.toFixed(2)}` : '';

        const html = `
            <li class="list-group-item d-flex justify-content-between align-items-center"
                data-produto-id="${id}"
                data-nome="${nome}"
                data-quantidade="${qtd}"
                data-valor-unitario="${valor}"
                data-desconto="${descontoTotal}"
                data-unidade="${unidade}">
                
                <div>
                    <strong>${nome}</strong><br>
                    <small>${qtd} ${unidade} x R$ ${valor.toFixed(2)}${descontoStr}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill">R$ ${total.toFixed(2)}</span>
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
        if (itens.length === 0) {
            Utils.showToast('Nenhum item no pedido.');
            return;
        }

        const $tbody = $('#modalConfirmarPedido table tbody');
        $tbody.empty(); // limpa o conteúdo atual

        // Renderiza até os 3 primeiros itens
        itens.slice(0, 3).forEach(item => {
            $tbody.append(`
                <tr class="item-pedido">
                    <td colspan="2">${item.nome} - ${item.quantidade} ${item.unidade}</td>
                </tr>
            `);
        });

        // Se houver mais de 3 itens, mostra o "+N itens"
        if (itens.length > 3) {
            const restante = itens.length - 3;
            const texto = restante === 1 ? 'item' : 'itens';
            $tbody.append(`<tr class="item-pedido"><td colspan="2">+${restante} ${texto}</td></tr>`);
        }

        // Totalizadores
        $tbody.append(`
            <tr class="totalizador">
                <td class="text-start"><b>Subtotal</b></td>
                <td class="text-end" id="resumo-subtotal">${$('#pedido-subtotal').text()}</td>
            </tr>
            <tr class="totalizador">
                <td class="text-start"><b>Descontos</b></td>
                <td class="text-end" id="resumo-descontos">${$('#pedido-descontos').text()}</td>
            </tr>
            <tr class="totalizador">
                <td class="text-start"><b>Total</b></td>
                <td class="text-end" id="resumo-total">${$('#pedido-total').text()}</td>
            </tr>
        `);

        this.$selectFormaPagamento.val('');

        $('#modalConfirmarPedido').modal('show');
    }

    limparCarrinho() {
        this.$listaItens.empty();
        localStorage.removeItem('itensPedido');
        this.atualizarTotais();
    }

    handleClickBtnConfirmarPedido() {
        const formaPagamentoId = this.$selectFormaPagamento.val();
        const itens = this.obterItens();

        if (!formaPagamentoId) {
            Utils.showToast('Selecione a forma de pagamento.');
            return;
        }

        $.post('/pedido/store', {
            forma_pagamento_id: formaPagamentoId,
            itens: JSON.stringify(itens)
        }, (res) => {
            if (res.success) {
                $.getJSON(`/pedido/getPdfLink/${res.pedido_id}`, (pdfData) => {
                    if (pdfData.url) {
                        window.open(pdfData.url, '_blank');
                        $('#modalConfirmarPedido').modal('hide');
                        this.limparCarrinho();
                    } else {
                        Utils.showToast('Erro ao gerar o PDF.');
                    }
                });
            } else {
                Utils.showToast('Erro ao salvar o pedido.');
            }
        }, 'json');
    }

    handleClickBtnCarrinho() {
        this.carrinho = !this.carrinho;
        if (this.carrinho) {
            this.$categoriasProdutosContainer.addClass('d-none');
            this.$carrinhoContainer.removeClass('d-none');
        } else {
            this.$carrinhoContainer.addClass('d-none');
            this.$categoriasProdutosContainer.removeClass('d-none');
        }
    }

    handleClickCarrinhoBtnVoltar() {
        this.$carrinhoContainer.addClass('d-none');
        this.$categoriasProdutosContainer.removeClass('d-none');
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
            const descontoStr = item.desconto > 0 ? ` - Desc: R$ ${item.desconto.toFixed(2)}` : '';

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
                        <small>${item.quantidade} ${item.unidade} x R$ ${item.valorUnitario.toFixed(2)}${descontoStr}</small>
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
