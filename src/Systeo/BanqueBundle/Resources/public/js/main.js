$(document).ready(function () {
    
    CompteCaisse()
    
    $('body').on('change', '#banque_operation_banqueCompte', function () {
         CompteCaisse();
    });
    
    $('body').on('click', '.delete_line_csv', function () {

        $(this).parent('td').parent('tr').fadeOut(1000, function () {
            $(this).remove();
        });

    });

    $('body').on('click', '#recherche-operation-bancaire-ajax', function () {

        $.ajax({
            url: $('#Recherche-Operation-Bancaire').attr('action'),
            data: $('#Recherche-Operation-Bancaire').serialize(),
            type: 'POST',
            cache: false,
            beforeSend: function (xhr) {
                $('#myModalAffectationBody').css('opacity','0.8');
            },
            success: function (html) {
                $('#myModalAffectationBody').html(html);
                $('#myModalAffectationBody').css('opacity','1');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#myModalAffectationBody').css('opacity','1');
            }
        });

    });
    
    
    $('body').on('click', '.navigation-ajax a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            beforeSend: function (xhr) {
                $('#myModalAffectationBody').css('opacity','0.8');
            },
            success: function (html) {
                $('#myModalAffectationBody').html(html);
                $('#myModalAffectationBody').css('opacity','1');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#myModalAffectationBody').css('opacity','1');
            }
        });

    });
});

function getCompteValeur(value = null) {
    $('#compte-search').val(value);
}

function CompteCaisse(){
    if($('#banque_operation_banqueCompte').val() === "1"){
        $('#banque_operation_dateValeur').val('').parent('div').fadeOut();
    }else{
        $('#banque_operation_dateValeur').parent('div').fadeIn();
    }
}