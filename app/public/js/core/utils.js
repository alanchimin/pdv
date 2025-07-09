window.Utils = window.Utils || {};

Utils.addNewSelectOption = function($select, value, text) {
    $select.selectpicker('destroy');
    const option = new Option(text, value);
    $select.append(option);
    $select.val(value);
    $select.selectpicker();
};

Utils.showAlert = function($container, message, type = 'danger', duration = 5000) {
    $container.empty();
    const alertId = `alert-${Date.now()}`;
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $container.append($(alertHtml));

    if (duration > 0) {
        setTimeout(() => {
            const alertEl = $(`#${alertId}`)[0];
            if (alertEl) {
                const alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
                alertInstance.close();
            }
        }, duration);
    }
};
