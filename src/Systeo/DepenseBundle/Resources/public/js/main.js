$(document).ready(function(){
    
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
        
        $('#myModalListeTier').modal('toggle');
    });
    
    $('body').on('blur','#systeo_depense_montantHt, #systeo_depense_montantTva',function(){
        
        var montantHt = parseFloat($('#systeo_depense_montantHt').val());
        var montantTva = parseFloat($('#systeo_depense_montantTva').val());
        
        if(montantTva === 0){
            $('#systeo_depense_montantTtc').val((montantHt+montantTva).toFixed(3));
        }
        
    });
    
    $('body').on('click', '.affecter-reglement', function () {
        var url = $(this).data('url');
        
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            beforeSend: function (xhr) {
               $('#myModalAffectationBody').html(''); 
            },
            success: function (html) {
                $('#myModalAffectationBody').html(html);
                $('#myModalAffectation').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#myModalAffectationBody').html('');
            }
        });
       
    });
    
    $('body').on('click', '.modal-regelement', function () {
        var url = $(this).data('url');
        var label = $(this).data('label');
        
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            beforeSend: function (xhr) {
               $('#myModalRegelmentBody').html('');
               $('#myModalRegelmentLabel').html(''); 
            },
            success: function (html) {
                $('#myModalRegelmentBody').html(html);
                $('#myModalRegelmentLabel').html(label);
                $('#myModalRegelment').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#myModalRegelmentBody').html('');
                $('#myModalRegelmentLabel').html(''); 
            }
        });
       
    });
    
    $('body').on('focusin', '.datepicker', function () {
        $(this).datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            language:'fr'
        });
    });
});

function getDepenseCategorySearch(valeur = null){
    
   $('#depense_search_depenseCategory').val(valeur);
   
}
