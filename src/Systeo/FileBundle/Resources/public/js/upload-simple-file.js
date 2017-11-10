$(document).ready(function(){
    
    $('body').on('click','#new_file',function(){
        $('#simple_file_filename').trigger('click');
    });
    
    $('body').on('change','#simple_file_filename',function(){
        
        var fd = new FormData(document.getElementById("simple-ajax-form"));
        var url = $('#new_file').data('url');
        var tmpFolder =  $('#new_file').data('tmp');
        var field = $('#new_file').data('target-field');
        var destination = $('#new_file').data('target-destination');
        
        console.log('simple-ajax-form');
        
        $.ajax({
            type: "POST",
            cache: false,
            data: fd,
            url: url,
            processData: false,  
            contentType: false,  
            beforeSend: function(XMLHttpRequest) {
               $('#simple_file_alert').hide().html('');
               $('#icones_upload_loading').fadeIn();
            },
            success: function(msg) {
                $('#icones_upload_loading').hide();
                $('#icones_upload_success').fadeIn().delay(1000).fadeOut();
                $('#simple_file_filename').val('');
                
                var obj = JSON.parse(msg);
             
                if(obj.success && obj.is_image){
                    
                    $(field).val(obj.file_name);
                    $(destination).html('<img src="'+tmpFolder+'/'+obj.file_name+'">');
                }else{
                    $('#upload_file_error_message').html("Vous ne pouvez pas uploader des fichier avec cette extension.").fadeIn(function(){
                        $(this).delay(5000).fadeOut();
                    });
                }
                
            },
            error: function(msg){
                $('#simple_file_filename').val('');
                $('#icones_upload_loading').hide();
                $('#icones_upload_error').fadeIn().delay(1000).fadeOut();
                $('#upload_file_error_message').html("Une erreur est survenu, veuillez SVP ressayer ultérieurement.").fadeIn(function(){
                    $(this).delay(5000).fadeOut();
                });
            }
        });
    });
    
    
    
});