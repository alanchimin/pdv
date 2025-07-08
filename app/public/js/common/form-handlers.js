function initImagemPreview() {
    const $url = $('#imagem-url');
    const $preview = $('#imagem-preview');
    if (!$url.length || !$preview.length) return;

    $url.on('input', function () {
        const url = $(this).val();
        $preview.attr('src', url).toggle(!!url);
    });
}

function initRadioToggle() {
    const $radioUrl = $('#radio-url');
    const $radioUpload = $('#radio-upload');
    const $urlInput = $('#imagem-url');
    const $uploadInput = $('#imagem-upload');
    const $preview = $('#imagem-preview');

    if (!$radioUrl.length || !$radioUpload.length) return;

    $radioUrl.on('change', function () {
        if (this.checked) {
            $urlInput.show();
            $uploadInput.hide();
            $preview.hide();
        }
    });

    $radioUpload.on('change', function () {
        if (this.checked) {
            $uploadInput.show();
            $urlInput.hide();
            $preview.hide();
        }
    });
}

function initUploadPreview() {
    const $upload = $('#imagem-upload');
    const $preview = $('#imagem-preview');
    if (!$upload.length || !$preview.length) return;

    $upload.on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $preview.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
}

function escapeHtml(text) {
    return text.replace(/[&<>"'\/]/g, function (s) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;'
        }[s];
    });
} 

// Exporta funções globais para uso por página
window.FormHandlers = {
    initImagemPreview,
    initRadioToggle,
    initUploadPreview,
    escapeHtml
};
