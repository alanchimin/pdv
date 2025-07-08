$(document).ready(function () {
    // Filtro de categoria
    $('.btn-group [data-categoria-id]').on('click', function () {
        const categoriaId = $(this).data('categoria-id');

        $('.btn-group .btn').removeClass('active');
        $(this).addClass('active');

        $('.produto-item').each(function () {
            const prodCatId = $(this).data('categoria-id');
            $(this).closest('.col-md-3').toggle(categoriaId === 0 || prodCatId == categoriaId);
        });
    });

    // Abrir modal ao clicar no produto
    $('.produto-item').on('click', function () {
        const id = $(this).data('id');
        const nome = $(this).data('nome');
        const valor = parseFloat($(this).data('valor'));

        $('#produto-id').val(id);
        $('#produto-nome').val(nome);
        $('#quantidade').val(1);
        $('#desconto-porcentagem').val(0);
        $('#desconto-reais').val(0.00);
        $('#btn-adicionar').data('valor', valor);

        $('#modalProduto').modal('show');
    });

    // Alternar entre os tipos de desconto
    $('input[name="tipo-desconto"]').on('change', function () {
        const tipo = $(this).val();
        if (tipo === 'percentual') {
            $('#campo-desconto-percentual').removeClass('d-none');
            $('#campo-desconto-reais').addClass('d-none');
        } else {
            $('#campo-desconto-reais').removeClass('d-none');
            $('#campo-desconto-percentual').addClass('d-none');
        }
    });


    $('#btn-adicionar').on('click', function () {
        limparErro();

        const id = $('#produto-id').val();
        const nome = $('#produto-nome').val();
        const quantidade = parseInt($('#quantidade').val());
        const valor = parseFloat($(this).data('valor'));
        const tipoDesconto = $('input[name="tipo-desconto"]:checked').val();

        if (!quantidade || quantidade <= 0) {
            mostrarErro('Informe uma quantidade válida maior que zero.');
            return;
        }

        const valorTotal = valor * quantidade;
        let valorDesconto = 0;

        if (tipoDesconto === 'percentual') {
            const descontoPct = parseFloat($('#desconto-porcentagem').val()) || 0;
            if (descontoPct < 0 || descontoPct > 100) {
                mostrarErro('O desconto percentual deve estar entre 0% e 100%.');
                return;
            }
            valorDesconto = (valorTotal * descontoPct) / 100;
        } else {
            valorDesconto = parseFloat($('#desconto-reais').val()) || 0;
        }

        if (valorDesconto > valorTotal) {
            mostrarErro('O desconto não pode ser maior que o valor total do produto.');
            return;
        }

        const valorFinal = valorTotal - valorDesconto;

        const itemHtml = `
            <li class="list-group-item d-flex justify-content-between align-items-center" data-produto-id="${id}">
                <div>
                    <strong>${nome}</strong><br>
                    <small>${quantidade} un x R$ ${valor.toFixed(2)} - Desc: R$ ${valorDesconto.toFixed(2)}</small>
                </div>
                <span class="badge bg-primary rounded-pill">R$ ${valorFinal.toFixed(2)}</span>
            </li>
        `;


        $('#lista-itens').append(itemHtml);
        $('#modalProduto').modal('hide');
        atualizarTotais();

        // Scroll para o final da lista
        const lista = $('#lista-itens').closest('.border');
        lista.animate({ scrollTop: lista.prop('scrollHeight') }, 300);

        salvarListaEmLocalStorage();
    });

    $('#btn-finalizar-pedido').on('click', function () {
        const itens = JSON.parse(localStorage.getItem('itensPedido') || '[]');
        if (itens.length === 0) {
            mostrarErroFinalizar('Nenhum item no pedido.');
            return;
        }

        $.post('/pedido/store', { itens: JSON.stringify(itens) }, function (response) {
            if (response.success) {
                $.getJSON('/pedido/getPdfLink/' + response.pedido_id, function (pdfData) {
                    if (pdfData.url) {
                        window.open(pdfData.url, '_blank');
                        limparCarrinho();
                    } else {
                        mostrarErroFinalizar('Erro ao gerar o PDF.');
                    }
                });
            } else {
                mostrarErroFinalizar('Erro ao salvar o pedido.');
            }
        }, 'json');
    });


    $('#btn-confirmar-limpeza').on('click', function () {
        limparCarrinho();
    });

    function limparCarrinho() {
        $('#lista-itens').empty();
        localStorage.removeItem('itensPedido');
        atualizarTotais();
    }

    function mostrarErro(mensagem) {
        const erro = $('#mensagem-erro');
        erro.text(mensagem).removeClass('d-none');
    }

    function limparErro() {
        $('#mensagem-erro').addClass('d-none').text('');
    }

    function mostrarErroFinalizar(mensagem) {
        const div = $('#mensagem-finalizar');
        div.text(mensagem).removeClass('d-none');
        setTimeout(() => div.addClass('d-none').text(''), 4000);
    }


    function salvarListaEmLocalStorage() {
        const itens = [];

        $('#lista-itens li').each(function () {
            const produtoId = $(this).data('produto-id');
            const texto = $(this).find('small').text();
            const nome = $(this).find('strong').text();
            const match = texto.match(/(\d+) un x R\$ ([\d,.]+) - Desc: R\$ ([\d,.]+)/);

            if (match) {
                itens.push({
                    produtoId,
                    nome,
                    quantidade: parseInt(match[1]),
                    valorUnitario: parseFloat(match[2].replace(',', '.')),
                    desconto: parseFloat(match[3].replace(',', '.'))
                });
            }
        });

        localStorage.setItem('itensPedido', JSON.stringify(itens));
    }

    function carregarListaDoLocalStorage() {
        const itens = JSON.parse(localStorage.getItem('itensPedido') || '[]');
        $('#lista-itens').empty();

        itens.forEach(item => {
            const total = item.valorUnitario * item.quantidade;
            const final = total - item.desconto;

            const html = `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.nome}</strong><br>
                        <small>${item.quantidade} un x R$ ${item.valorUnitario.toFixed(2)} - Desc: R$ ${item.desconto.toFixed(2)}</small>
                    </div>
                    <span class="badge bg-primary rounded-pill">R$ ${final.toFixed(2)}</span>
                </li>
            `;
            $('#lista-itens').append(html);
        });

        atualizarTotais();
    }

    function atualizarTotais() {
        let subtotal = 0;
        let descontos = 0;

        const items = $('#lista-itens li');
        items.each(function () {
            const texto = $(this).find('small').text();
            const total = parseFloat($(this).find('.badge').text().replace('R$', '').replace(',', '.'));
            const match = texto.match(/(\d+) un x R\$ ([\d,.]+) - Desc: R\$ ([\d,.]+)/);

            if (match) {
                const qtd = parseInt(match[1]);
                const valor = parseFloat(match[2].replace(',', '.'));
                const desc = parseFloat(match[3].replace(',', '.'));
                subtotal += qtd * valor;
                descontos += desc;
            }
        });

        const totalFinal = subtotal - descontos;

        $('#pedido-subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
        $('#pedido-descontos').text(`R$ ${descontos.toFixed(2).replace('.', ',')}`);
        $('#pedido-total').text(`R$ ${totalFinal.toFixed(2).replace('.', ',')}`);

        // Placeholder de lista
        $('#lista-placeholder').toggle(items.length === 0);
    }

    function carregarTela() {
        atualizarTotais();
        carregarListaDoLocalStorage();
    }

    carregarTela();
});
