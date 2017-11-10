$(document).ready(function(){
    
    $('body').on('click','#new_file',function(){
        $('#file_filename').trigger('click');
    });
    
    $('body').on('change','#file_filename',function(){
        var file_name = $('#file_filename').val();
        
        file_name = file_name.split('\\');
        
        $('#file_name_value').html(file_name[file_name.length - 1]);
        $('#start_upload').fadeIn();
    });
    
    $('body').on('click','#start_upload',function(){
        
        var fd = new FormData(document.getElementById("ajax_add_file_form"));
        var url = $(this).data('url');
        
        if($('#file_filename').val() === ''){
            $('#upload_file_error_message').html('Veuillez sélectionner un fichier!').fadeIn();
            return;
        }
        
        $.ajax({
            type: "POST",
            cache: false,
            data: fd,
            url: url,
            processData: false,  
            contentType: false,  
            beforeSend: function(XMLHttpRequest) {
               $('#start_upload').attr('disabled',true);
               $('#upload_file_error_message').hide().html('');
               $('#ajax_file_upload_loading').fadeIn();
            },
            success: function(msg) {
                $('#ajax_file_upload_loading').hide();
                $('#start_upload').attr('disabled',false);
                $('#ajax_file_upload_success').fadeIn().delay(1000).fadeOut();
                $('#file_filename').val('');
                $('#file_name_value').html('');
                
                if(msg === 'photo'){
                    $('#photos_gallery').html('');
                    $('#loading_photos_gallery').fadeIn();
                    display_files('photo');
                }else if(msg === 'doc'){
                    $('#files_list').html('');
                    $('#loading_files_list').fadeIn();
                    display_files('doc');
                }else if(msg === 'erreur-format'){
                    $('#upload_file_error_message').html("Vous ne pouvez pas uploader des fichier avec cette extension.").fadeIn(function(){
                        $(this).delay(5000).fadeOut();
                    });
                }
                
            },
            error: function(msg){
                $('#file_filename').val('');
                $('#file_name_value').html('');
                $('#ajax_file_upload_loading').hide();
                $('#start_upload').attr('disabled',false);
                $('#upload_file_error_message').html("Une erreur est survenu, veuillez SVP ressayer ultérieurement.").fadeIn(function(){
                    $(this).delay(5000).fadeOut();
                });
            }
        });
    });
    
    $('body').on('click','.delete_image_galler_display',function(){
       
       if(confirm('Voulez-vous vraiement supprimer cette image?')){
           
           var url = $(this).data('url');
           var id = $(this).data('file-id');
           
           $.ajax({
                type: "POST",
                cache: false,
                url: url,
                processData: false,  
                contentType: false, 
                success: function(msg) {
                    $('#image-gallery-'+id).fadeOut();
                },
                error: function(msg){
                    alert('L\'image n\'a pas pu être supprimée!');
                }
            });
           
       }
        
    });
    
    $('body').on('click','.delete_file_icone',function(){
       
       if(confirm('Voulez-vous vraiement supprimer ce fichier?')){
           
           var url = $(this).data('url');
           var id = $(this).data('file-id');
           
           $.ajax({
                type: "POST",
                cache: false,
                url: url,
                processData: false,  
                contentType: false, 
                success: function(msg) {
                    $('#file-'+id).fadeOut();
                },
                error: function(msg){
                    alert('Le fichier n\'a pas pu être supprimé!');
                }
            });
           
       }
        
    });
    
    $('body').on('click','.edit_file_icone, .edit_image_galler_display',function(){
        
        $('#modal_edit_ajax_file').modal('show');
        
        var url = $(this).data('url');
        var id = $(this).data('file-id');
        
        $.ajax({
            type: "GET",
            cache: false,
            url: url,
            processData: false,  
            contentType: false, 
            success: function(msg) {
                $('#modal-edit-ajax-file-body').html(msg);
            }
        });
        
    });
    
    $('body').on('click','#submit_edit_ajax_file',function(){
        $.ajax({
            type: "POST",
            cache: false,
            url: $('#file-edit-form').attr('action'),
            data:$('#file-edit-form').serialize(),
            success: function(msg) {
                $('#modal-edit-ajax-file-body').html(msg);
            }
        });
    });
    
    $('body').on('mouseover','#list_file_links>li',function(){
        $(this).children('.delete_file_icone').css('visibility','visible');
        $(this).children('.edit_file_icone').css('visibility','visible');
    });
    
    $('body').on('mouseout','#list_file_links>li',function(){
        $('.delete_file_icone').css('visibility','hidden');
        $('.edit_file_icone').css('visibility','hidden');
    });
    
});

function load_view_ajax_file() {

    $.ajax({
        type: "GET",
        cache: false,
        url: AjaxFileConf.uploadPath+'?entity='+AjaxFileConf.entity+'&entity_id='+AjaxFileConf.entity_id+'&liste_doc='+AjaxFileConf.diplayDocs+'&gallery_photo='+AjaxFileConf.displayGallery,
        success: function (msg) {
            $(AjaxFileConf.container).html(msg);
            $('#file_entity').val(AjaxFileConf.entity);
            $('#file_entityId').val(AjaxFileConf.entity_id);
        }
    });

}

function display_files(type){
    
    $.ajax({
        type: "GET",
        cache: false,
        url: AjaxFileConf.docsGaleryLoadingPath+'?entity='+AjaxFileConf.entity+'&entity_id='+AjaxFileConf.entity_id+'&type='+type,
        success: function (msg) {
            if(type === 'doc'){
                $('#loading_files_list').fadeOut();
                $('#files_list').html(msg);
            }else if(type === 'photo'){
                $('#loading_photos_gallery').fadeOut();
                $('#photos_gallery').html(msg);
            }
        },
        error: function(){
            $('#loading_files_list').fadeOut();
            $('#loading_photos_gallery').fadeOut();
        }
    });
    
}