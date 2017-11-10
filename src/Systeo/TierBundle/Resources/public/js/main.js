$(document).ready(function(){
    
    $('body').on('change','#systeo_tier_type',function(){
        tierTypeChanges($(this).val());
    });
    
     $('body').on('click', '.navigation-ajax a', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'GET',
                    cache: false,
                    beforeSend: function (xhr) {
                        $('#myModalListeTierBody').css('opacity','0.8');
                    },
                    success: function (html) {
                        $('#myModalListeTierBody').html(html);
                        $('#myModalListeTierBody').css('opacity','1');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#myModalListeTierBody').css('opacity','1');
                    }
                });

            });
            
            
            $('body').on('click', '#recherche-tier-ajax', function () {

                $.ajax({
                    url: $('#Recherche-tier').attr('action'),
                    data: $('#Recherche-tier').serialize(),
                    type: 'POST',
                    cache: false,
                    beforeSend: function (xhr) {
                        $('#myModalListeTierBody').css('opacity','0.8');
                    },
                    success: function (html) {
                        $('#myModalListeTierBody').html(html);
                        $('#myModalListeTierBody').css('opacity','1');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#myModalListeTierBody').css('opacity','1');
                    }
                });
            });
});

function tierTypeChanges(type){
    
    if(type === ""){
        type = 'm';
    }
    
    if(type === "m"){
        $('.tier-personne-physique').hide();
        $('.tier-personne-physique input').val('');
        
        $('.tier-personne-morale').fadeIn();
    }
    
    else if(type === "p"){
        $('.tier-personne-morale').hide();
        $('.tier-personne-morale input').val('');
        
        $('.tier-personne-physique').fadeIn();
    }
    
}
