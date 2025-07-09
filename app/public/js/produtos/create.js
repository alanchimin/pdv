$(document).ready(function () {
    // Inicializa os selects com bootstrap-select
    const $selects = $('.selectpicker');
    if ($selects.length) $selects.selectpicker();

    initImagemPreview();
    initRadioToggle();
    initUploadPreview();
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
