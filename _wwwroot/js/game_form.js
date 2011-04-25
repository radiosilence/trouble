$(function() {
    function set_invite_only_state(v,hide) { 
        if(v == 0) {
            if(hide) {
                $('#p_entry_fee').hide();
                $('#p_password').hide();
            } else { 
                $('#p_entry_fee').fadeOut();
                $('#p_password').fadeOut();
            }
        } else if(v == 1) {
            if(hide) {
                $('#p_entry_fee').hide();
                $('#p_password').show();
            } else { 
                $('#p_entry_fee').fadeOut(function(){
                   $('#p_password').fadeIn();  
                });
            }
        } else if(v == 2) {
            if(hide) {
                $('#p_entry_fee').show();
                $('#p_password').hide();
            } else { 
                $('#p_password').fadeOut(function(){
                   $('#p_entry_fee').fadeIn();
                });
            }
        }
    }
    $('#submit_game_form').click(function(data) {
        var d = {};
        $.each($('#game_form').serializeArray(), function(i, field) {
            d[field.name] = field.value;
        });
        dialogResponse('/put/save_game', d);
        return false;
    });
    
    $("div#tabs_game_admin").tabs({
        cookie: {
            name: 'tabs_game_admin'
        }
    });
    $("div.accordion").accordion({
        autoHeight: false,
        navigation: true
    });
    $("#invite_only").buttonset();


    $("input[name=invite_only]").change(function() {
       set_invite_only_state($(this).val());
    });
    set_invite_only_state($("input[name=invite_only]:checked").val(), true);
});