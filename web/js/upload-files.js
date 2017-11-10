$(document).ready(function(){
    
     
    
    
    $('body').on('click','.simple_file_select',function(){
        
        var upload_button = $(this).next('.simple_file_upload').first();
        var form_id = upload_button.data('target-form');
        var file_input = $('#'+form_id+'>div').children('input').first();
        
        file_input.trigger('click');
        
    });
    
    $('body').on('change','.simple-file-field',function(){
       
        var form_id = $(this).closest('form').attr('id');
        
        var upload_button;
        
        $('.simple_file_upload').each(function(){
            if($(this).data('target-form') === form_id){
                upload_button = $(this);
            }
        });
        
        var selected_file = upload_button.closest('div').children('.selected_file').first();
        var file_name = $(this).val();
        
        file_name = file_name.split('\\');
        
        
        selected_file.html(file_name[file_name.length - 1]);
        upload_button.fadeIn();
        selected_file.fadeIn();
        
    });
    
    $('body').on('click','.simple_file_upload',function(){
        
        var form_id = $(this).data('target-form');
        var redirection = $(this).data('redirection');
        var file_input = $('#'+form_id+'>div').children('input').first();
        var upload_button = $(this);
        var error_message_alert = $(this).closest('div').children('.simple_file_alert').first();
        var selected_file = $(this).closest('div').children('.selected_file').first();
        var icones =  $(this).closest('div').children('.icones_file_upload').first().children('.ajax_simple_file_icones_loading');
        
            
        var icon_loading = icones.first();
        var icon_success = icon_loading.next();
        var icon_error = icones.last();
        
        var destination_space = $(this).closest('div').next('.conteneur-fichier').first();
        
        var type = $(this).data('type');
        var limit = $(this).data('limit');
        
        if(type === 'multiple'){
            var nombre = destination_space.children('.photo-gallerie-tmp').length;
            if(nombre>=limit){
                alert('Vous ne pouvez pas uploader au de là de '+limit+' images!');
                return false;
            }
        }
        
        if(file_input.val()!==""){
 
            var data = new FormData(document.getElementById(form_id));
            var url= $(this).data('url');
            
            
            $.ajax({
                type: "POST",
                cache: false,
                data: data,
                url: url,
                processData: false,  
                contentType: false,  
                beforeSend: function(XMLHttpRequest) {
                   upload_button.attr('disabled',true);
                   error_message_alert.hide().html('');
                   icon_loading.fadeIn();
                },
                success: function(msg) {
                    icon_loading.hide();
                    upload_button.attr('disabled',false);
                    icon_success.fadeIn().delay(1000).fadeOut();
                    file_input.val('');
                    selected_file.html('');
                    
                    var obj = JSON.parse(msg);
                    
                    if(obj.success === true){
                        window.location.replace(redirection+'/'+obj.file_name); 
                    }
        
                },
                error: function(msg){
                    icon_error.fadeIn().delay(1000).fadeOut();
                    file_input.val('');
                    selected_file.html('');
                    icon_loading.hide();
                    upload_button.attr('disabled',false);
                    error_message_alert.html(msg).fadeIn();
                }
            });
            
        }else{
            error_message_alert.html('Veuillez sélectionner un fichier!').fadeIn();
        }
    });
    
    
});

function traitement_post_upload(msg,destination_space,target_field,type){
    
    var obj = JSON.parse(msg);
    
    if(obj.success === true){
        var modele = destination_space.data('prototype');

        if(obj.is_image === false){
            var modif1 = modele.replace(/_extension_/g, obj.extension+'.png');
            var modif2 = modif1.replace(/_file_/g, obj.file_name);
        }else{
            var available_space = parseInt(destination_space.width())*parseFloat(obj.ratio)*0.9;
            var modif1 = modele.replace(/_wh_/g, available_space);
            var modif2 = modif1.replace(/_image_/g, obj.file_name);
            
        }
        
        if(type === "multiple"){
            destination_space.prepend(modif2);
            
            if($('#'+target_field).val()===""){
                $('#'+target_field).val(obj.file_name);
            }else{
                $('#'+target_field).val($('#'+target_field).val()+'_|_|_'+obj.file_name);   
            }
            
        }else{
            destination_space.html(modif2);
            $('#'+target_field).val(obj.file_name);
        }
        
        
    }else{
        
    }
    
}


function deleteFirstEntryCollectionForm(className){
    $('.'+className).each(function(){
        if($(this).val()===""){
            $(this).parent('div').parent('div').remove();
        }
    });
}
