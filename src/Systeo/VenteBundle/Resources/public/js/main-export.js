var decimal = 2;
var empty_flied = '0.00';

$(document).ready(function () {
    
    $('.glyphicon-pencil-btn').hide();
    
    $('body').on('click', '.btn-affecter-export', function () {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            beforeSend: function (xhr) {
                $('#myModalAffectationExportBody').html('');
            },
            success: function (html) {
                $('#myModalAffectationExportBody').html(html);
                $('#myModalAffectationExportEditor').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#myModalAffectationExportBody').html('');
            }
        });
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
        $('#' + $('#affectationTier').data('mf')).val($(this).data('mf'));
        $('#' + $('#affectationTier').data('adresse')).val($(this).data('adresse'));
        $('#myModalListeTier').modal('toggle');
    });
    $('body').on('focusin', '.datepicker', function () {
        $(this).datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'fr'
        });
    });
    
    
    $('body').on('click','#btn-ajouter-ligne',function(){
        // Get the data-prototype explained earlier
        var prototype = $('#prototype-ligne').data('prototype');

        // get the new index
        var index = $('#prototype-ligne').data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $('#prototype-ligne').data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('#lignes-pieces-body').append(newForm);
        $('#edit_ligne_'+index).hide();
    });
    
    $('body').on('click','.delete-ligne-piece',function(){
        $(this).parent('td').parent("tr").fadeOut("normal", function() {
            $(this).remove();
            calculerTotalGeneral();
        });
        
    });
    
    $('body').on('focus click','.total-ht-ligne',function(){
        $(this).blur();
    });
    
    $('body').on('keyup','.prix-ht-ligne, .quantite-ligne',function(){
       
        var tr = $(this).parent('td').parent('tr');
        caculerTotalLigne(tr);
    });
    
    $('body').on('change','.tva-ligne',function(){
       
        var tr = $(this).parent('td').parent('tr');
        caculerTotalLigne(tr);
    });
    
    $('body').on('click','.glyphicon-screenshot-btn',function(){
        
        var index = $(this).data('index');
        var content = $('#systeo_ventebundle_piece_pieceExportLignes_'+index+'_name').val();

        $('#systeo_ventebundle_piece_pieceExportLignes_'+index+'_name').hide();
        $('#content_name_html_'+index).html(content).show();
        
        $(this).hide();
        $("#edit_ligne_"+index).show();
    });
    
    $('body').on('click','.glyphicon-pencil-btn',function(){
        
        var index = $(this).data('index');

        $('#content_name_html_'+index).hide();
        $('#systeo_ventebundle_piece_pieceExportLignes_'+index+'_name').show();
        
        $(this).hide();
        $("#view_ligne_"+index).show();
        
    });
    
    $('body').on('click','.glyphicon-wrench-btn',function(){
        
        var index = $(this).data('index');
        $('#valider-ckeditor').attr('index',index);
        $('#myModalLigneEditor').modal('show');
        
        CKEDITOR.instances['systeo_designation_designation'].setData($('#systeo_ventebundle_piece_pieceExportLignes_'+index+'_name').val());
        
    });
    
    $('body').on('click','#valider-ckeditor',function(){
        var index = $(this).attr('index');
        var data = CKEDITOR.instances['systeo_designation_designation'].getData();
        
        $('#systeo_ventebundle_piece_pieceExportLignes_'+index+'_name').val(data).hide();
        $('#content_name_html_'+index).html(data).show();
        
        $('#view_ligne_'+index).hide();
        $('#edit_ligne_'+index).show();
        
        $('#myModalLigneEditor').modal('toggle');
        
    }); 
});

function calculerTotalGeneral(){
    var total_ht = 0;
    var total_tva = 0;
    var total_ttc = 0;
    
    $('.total-ht-ligne').each(function(){
        total_ht = total_ht + parseFloat($(this).val());
    });
    
    $('.total-tva-ligne').each(function(){
        total_tva = total_tva + parseFloat($(this).text());
    });
    
    $('.total-ttc-ligne').each(function(){
        total_ttc = total_ttc + parseFloat($(this).text());
    });
    
    
    if(!isNaN(total_ht)){
        $('#systeo_ventebundle_piece_montantHt').val(total_ht.toFixed(decimal));
    }else{
        $('#systeo_ventebundle_piece_montantHt').val(empty_flied);
    }
    
    if(!isNaN(total_tva)){
        $('#systeo_ventebundle_piece_montantTva').val(total_tva.toFixed(decimal));
    }else{
        $('#systeo_ventebundle_piece_montantTva').val(empty_flied);
    }
    
    if(!isNaN(total_ht) && !isNaN(total_tva)){
        
        total_ttc = total_ht + total_tva ;

        $('#systeo_ventebundle_piece_montantTtc').val(total_ttc.toFixed(decimal));
    }else{
        $('#systeo_ventebundle_piece_montantTtc').val(empty_flied);
    }
    
}

function caculerTotalLigne(tr){
    var prix = tr.children('td').children('.prix-ht-ligne').val();
    var qte = tr.children('td').children('.quantite-ligne').val();
    var taux_tva = tr.children('td').children('.tva-ligne').val();
    var total_ht =0; var total_tva =0; var total_ttc =0; 
    
    if(taux_tva === ""){
        taux_tva = 0;
    }
   
    tr.children('td').children('.total-ttc-ligne').text(total_ttc);
    
    if(!isNaN(qte) && !isNaN(prix)){
        total_ht = qte * prix;
        total_tva = total_ht * taux_tva / 100;
        total_ttc = total_ht + total_tva;
    }
    
    tr.children('td').children('.total-ht-ligne').val(total_ht.toFixed(decimal));
    tr.children('td').children('.total-tva-ligne').text(total_tva.toFixed(decimal));
    tr.children('td').children('.total-ttc-ligne').text(total_ttc.toFixed(decimal));
    
    calculerTotalGeneral();
}

function getSolde(form_id){
    
    var url = $('#'+form_id).attr('url');
    var data = $('#'+form_id).serialize();
    
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        cache: false,
        beforeSend: function (xhr) {
            
        },
        success: function (html) {
            $('#SoldeTotal').html('<b>'+html+'</b>');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            
        }
    });
}