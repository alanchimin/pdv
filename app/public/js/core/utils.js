window.Utils = window.Utils || {};

Utils.addNewSelectOption = function($select, value, text) {
    $select.selectpicker('destroy');
    const option = new Option(text, value);
    $select.append(option);
    $select.val(value);
    $select.selectpicker();
};

Utils.setSelectOption = function($select, value) {
    $select.selectpicker('destroy');
    $select.val(value);
    $select.selectpicker();
};

Utils.debounce = function (fn, delay = 300) {
    let timer = null;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
};

Utils.showToast = function(message, type = 'danger', duration = 5000) {
    const toastId = `toast-${Date.now()}`;
    const iconMap = {
        success: 'fa-check-circle',
        danger: 'fa-circle-exclamation',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };
    const icon = iconMap[type] || 'fa-info-circle';

    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0 shadow-sm mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fa-solid ${icon} me-2"></i> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    `;

    const $toast = $(toastHtml);
    $('#toast-container').append($toast);

    const bsToast = new bootstrap.Toast($toast[0], {
        autohide: duration > 0,
        delay: duration
    });

    bsToast.show();

    // Remover da DOM após ocultar
    $toast.on('hidden.bs.toast', () => $toast.remove());
};
