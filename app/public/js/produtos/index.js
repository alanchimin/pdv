$(document).ready(function () {
    const $busca = $('#busca');
    const $resultados = $('#resultados');

    if (!$busca.length || !$resultados.length) return;

    $busca.on('input', function () {
        const termo = $(this).val();
        if (termo.length >= 2) {
            $.get('/produto?q=' + encodeURIComponent(termo), function (data) {
                let html = '';
                data.forEach(produto => {
                    html += `<li>${window.FormHandlers.escapeHtml(produto.nome)} - R$ ${parseFloat(produto.valor_unitario).toFixed(2).replace('.', ',')}</li>`;
                });
                $resultados.html(html);
            });
        } else {
            $resultados.html('');
        }
    });
});
