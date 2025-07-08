$(document).ready(function () {
    $('.btn-group [data-categoria-id]').on('click', function () {
        const categoriaId = $(this).data('categoria-id');

        $('.btn-group .btn').removeClass('active');
        $(this).addClass('active');

        $('.produto-item').each(function () {
            const prodCatId = $(this).data('categoria-id');
            if (categoriaId === 0 || prodCatId == categoriaId) {
                $(this).closest('.col-md-3').show();
            } else {
                $(this).closest('.col-md-3').hide();
            }
        });
    });
});
