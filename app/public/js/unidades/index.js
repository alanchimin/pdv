let unidadeListagem;
$(() => {
    unidadeListagem = new ListagemBase({
        entidade:    'unidadeMedida',
        containerId: 'unidade-container',
        colunas: [
            { campo: 'unidade_medida_id' },
            { campo: 'nome' },
            { campo: 'simbolo' }
        ],
    });
});
