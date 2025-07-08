// Aguarda o DOM estar pronto
$(document).ready(function () {
    // Inicializa os selects com bootstrap-select (se presente)
    if ($('.selectpicker').length) {
        $('.selectpicker').selectpicker();
    }

    initImagemPreview();
    initRadioToggle();
    initUploadPreview();
    initAjaxBusca();
});

/** Pré-visualização de imagem por URL */
function initImagemPreview() {
    if (!$('#imagem-url').length || !$('#imagem-preview').length) return;

    $('#imagem-url').on('input', function () {
        const url = $(this).val();
        $('#imagem-preview').attr('src', url).toggle(!!url);
    });
}

/** Alterna campos de imagem entre URL e upload */
function initRadioToggle() {
    if (!$('#radio-url').length || !$('#radio-upload').length) return;

    $('#radio-url').on('change', function () {
        if (this.checked) {
            $('#imagem-url').show();
            $('#imagem-upload, #imagem-preview').hide();
        }
    });

    $('#radio-upload').on('change', function () {
        if (this.checked) {
            $('#imagem-upload').show();
            $('#imagem-url, #imagem-preview').hide();
        }
    });
}

/** Pré-visualização da imagem de upload */
function initUploadPreview() {
    if (!$('#imagem-upload').length || !$('#imagem-preview').length) return;

    $('#imagem-upload').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#imagem-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
}

/** Busca AJAX por produtos */
function initAjaxBusca() {
    const $busca = $('#busca');
    const $resultados = $('#resultados');

    if (!$busca.length || !$resultados.length) return;

    $busca.on('input', function () {
        const termo = $(this).val();

        if (termo.length >= 2) {
            $.get('index.php?c=produto&a=buscar&q=' + encodeURIComponent(termo), function (data) {
                let html = '';
                data.forEach(produto => {
                    html += `<li>${escapeHtml(produto.nome)} - R$ ${parseFloat(produto.valor_unitario).toFixed(2).replace('.', ',')}</li>`;
                });
                $resultados.html(html);
            });
        } else {
            $resultados.html('');
        }
    });
}

/** Escapa conteúdo HTML para evitar XSS */
function escapeHtml(text) {
    return text.replace(/[&<>"'\/]/g, function (s) {
        return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;'
        })[s];
    });
}
