$(document).ready(function () {
    $('body').on('click', '.delete_form_button', function () {
        if (confirm($(this).data('msg'))) {
            $(this).parent('form').submit();
        }
    });

    $(function () {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'fr'
        });
    });

});

function getSpecificUrl(url, destination) {
    $.ajax({
        type: "GET",
        cache: false,
        url: url,
        success: function (msg) {
            $(destination).html(msg);
        }
    });
}