$(function(){
    $('button.join_game').click(function(){
        data = {
            'id': $(this).attr('game_id')
        };
        dialogConfirm('Join Game',
            'Are you sure you wish to join this game?',
            function(d) {
                dialogResponse('/put/join_game', d, function(){
                    location.reload();
                });
            },
            data
        );
        return false;
    });

    $('button.leave_game').click(function(){
        data = {
            'id': $(this).attr('game_id')
        };
        dialogConfirm('Leave Game',
            'Are you sure you wish to leave this game?',
            function(d){
                dialogResponse('/put/leave_game', d, function(){
                    location.reload();
                });
            },
            data
        );
        return false;
    });
    $("#date").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#dialog-form").dialog({
        autoOpen: false,
        height: 500,
        width: 600,
        modal: true,
        buttons: {
            "Register Kill": function() {
                alert("boop beep");
            },
            Cancel: function() {
                $(this).dialog( "close" );
            }
        },
        open: function() {
            d = {
                'tok': $.cookie('tok')
            };
            $.getJSON('/get/weapon_list', d, function(data){
                var options = '';
                $.each(data, function(k,v) {
                    options += '<option value="'+v['id']+'">'+v['name']+'</option>';
                });
                $('#weapon').html($('#weapon').html()+options);
            });
        },
        close: function() {
            allFields.val("").removeClass( "ui-state-error" );
        }
    });
    $("#register_kill").click(function() {
        $("#date").datepicker('disable');
        $("#dialog-form").dialog("open");

        $("#date").datepicker('hide');
        $("#date").datepicker('enable');
    });

});