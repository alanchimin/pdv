let categoriaListagem;
$(() => {
    categoriaListagem = new ListagemBase({
        entidade:    'categoria',
        containerId: 'categoria-container',
        colunas: [
            { campo: 'categoria_id' },
            { campo: 'nome' }
        ],
    });
});
