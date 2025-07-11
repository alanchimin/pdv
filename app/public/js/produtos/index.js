let produtoListagem;
$(() => {
    produtoListagem = new ListagemBase({
        entidade: 'produto',
        containerId: 'produto-container',
        colunas: [
            { campo: 'produto_id' },
            { campo: 'nome' },
            { campo: 'categoria_nome' },
            { campo: 'simbolo' },
            { campo: 'valor_unitario' },
            { campo: 'desconto' }
        ]
    });
});
