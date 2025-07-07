// Bootstrap bundle placeholder// Aguarda o DOM estar pronto
$(document).ready(function () {
    // Pré-visualização de imagem (se presente na página)
    $('#imagem-url').on('input', function () {
        const url = $(this).val();
        if (url) {
            $('#imagem-preview').attr('src', url).show();
        } else {
            $('#imagem-preview').hide();
        }
    });

    $('#radio-url').on('change', function () {
        if ($(this).is(':checked')) {
            $('#imagem-url').show();
            $('#imagem-upload').hide();
            $('#imagem-preview').attr('src', $('#imagem-url').val()).show();
        }
    });

    $('#radio-upload').on('change', function () {
        if ($(this).is(':checked')) {
            $('#imagem-upload').show();
            $('#imagem-url').hide();
            $('#imagem-preview').hide();
        }
    });

    // Preview da imagem de upload
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

    // Busca AJAX de produtos (se campo existir)
    $('#busca').on('input', function () {
        const termo = $(this).val();
        if (termo.length >= 2) {
            $.get('index.php?c=produto&a=buscar&q=' + encodeURIComponent(termo), function (data) {
                let html = '';
                data.forEach(produto => {
                    html += `<li>${produto.nome} - R$ ${parseFloat(produto.valor_unitario).toFixed(2).replace('.', ',')}</li>`;
                });
                $('#resultados').html(html);
            });
        } else {
            $('#resultados').html('');
        }
    });
});
