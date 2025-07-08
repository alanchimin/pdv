$(document).ready(function () {
    if ($('.selectpicker').length) $('.selectpicker').selectpicker();
    const { initImagemPreview, initRadioToggle, initUploadPreview } = window.FormHandlers;
    initImagemPreview();
    initRadioToggle();
    initUploadPreview();
});
