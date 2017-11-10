$(document).ready(function () {
    
    $('body').on('click','#reglement-ajax-button',function(){
        
        var fd = new FormData(document.getElementById("reglement-ajax-form"));
        var url = $('#reglement-ajax-form').attr('action');
        
        $.ajax({
            type: "POST",
            cache: false,
            data: fd,
            url: url,
            processData: false,  
            contentType: false,  
            beforeSend: function(XMLHttpRequest) {
               $('#reglement-ajax-form').attr('disabled',true);
            },
            success: function(msg) {
                if(msg === 'ok'){
                    $('#myModalRegelment').modal('toggle');
                    location.reload();
                }else{
                    $('#myModalRegelmentBody').html(msg);
                }
                
            },
            error: function(msg){
                $('#reglement-ajax-form').attr('disabled',false);
                /*$('#upload_file_error_message').html("Une erreur est survenu, veuillez SVP ressayer ultérieurement.").fadeIn(function(){
                    $(this).delay(5000).fadeOut();
                });*/
            }
        });
    });
    
    $('body').on('change', '#systeo_reglementbundle_reglement_tier', function () {

        var $form = $(this).closest('form');

        var data = {};
        data[$('#systeo_reglementbundle_reglement_tier').attr('name')] = $('#systeo_reglementbundle_reglement_tier').val();
        data[$('#systeo_reglementbundle_reglement_piece').attr('name')] = $('#systeo_reglementbundle_reglement_piece').val();
        data[$('#systeo_reglementbundle_reglement_depense').attr('name')] = $('#systeo_reglementbundle_reglement_depense').val();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            beforeSend: function (xhr) {
                $('#loading-piece').show();
                $('#loading-depense').show();
            },
            success: function (html) {
                $('#loading-piece').fadeOut();
                $('#loading-depense').fadeOut();
                $('#systeo_reglementbundle_reglement_piece').replaceWith(
                        $(html).find('#systeo_reglementbundle_reglement_piece')
                        );
                $('#systeo_reglementbundle_reglement_depense').replaceWith(
                        $(html).find('#systeo_reglementbundle_reglement_depense')
                        );

            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#loading-piece').fadeOut();
                $('#loading-depense').fadeOut();
            }
        });

    });
    
    $('body').on('click', '#ajouter-tier', function () {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            beforeSend: function (xhr) {
                $('#myModalListeTierBody').html('');
            },
            success: function (html) {
                $('#myModalListeTierBody').html(html);
                $('#myModalListeTier').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#myModalListeTierBody').html('');
            }
        });
    });
    
    $('body').on('click', '.tier-affecter', function () {

        $('#' + $('#affectationTier').data('id')).val($(this).data('id'));
        $('#' + $('#affectationTier').data('name')).val($(this).data('name'));
        
        getTierDepenseFactur();
        
        $('#myModalListeTier').modal('toggle');
    });
    
    
    
});

function getTierDepenseFactur(){
        var $form = $('#systeo_reglementbundle_reglement_form');

        var data = {};
        data[$('#systeo_reglementbundle_reglement_tier').attr('name')] = $('#systeo_reglementbundle_reglement_tier').val();
        data[$('#systeo_reglementbundle_reglement_piece').attr('name')] = $('#systeo_reglementbundle_reglement_piece').val();
        data[$('#systeo_reglementbundle_reglement_depense').attr('name')] = $('#systeo_reglementbundle_reglement_depense').val();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            beforeSend: function (xhr) {
                $('#loading-piece').show();
                $('#loading-depense').show();
            },
            success: function (html) {
                $('#loading-piece').fadeOut();
                $('#loading-depense').fadeOut();
                $('#systeo_reglementbundle_reglement_piece').replaceWith(
                        $(html).find('#systeo_reglementbundle_reglement_piece')
                        );
                $('#systeo_reglementbundle_reglement_depense').replaceWith(
                        $(html).find('#systeo_reglementbundle_reglement_depense')
                        );

            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#loading-piece').fadeOut();
                $('#loading-depense').fadeOut();
            }
        });
}


