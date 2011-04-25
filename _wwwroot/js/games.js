$(function() {
    $("div#tabs_game").tabs({
      cookie: {
          name: 'tabs_games'
      }
    });
    $("div#games_list_tabs").tabs({
      cookie: {
          name: 'tabs_games'
      }
    });
});
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
    $(".datepick").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('.timepick').timepicker();
    $("#dialog-form").dialog({
        autoOpen: false,
        height: 500,
        width: 450,
        modal: true,
        buttons: {
            "Register Kill": function() {
                d = {
                    'game_id': $('#register_kill').attr('game_id'),
                    'pkn': $('input#pkn').val(),
                    'when_happened_time': $('input#when_happened_time').val(),
                    'when_happened_date': $('input#when_happened_date').val(),
                    'weapon': $('select#weapon').val(),
                    'description': $('textarea#description').val()
                };
                dialogResponse('/put/register_kill', d, function(){
                        location.reload();
                });
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
        $("#dialog-form").dialog("open");
    });
    $("select").change(function () {
        var str = "";
        if($(this).val() != 0) {
            $(this).removeClass('default');
        } else {
            $(this).addClass('default');
        }
        console.log();
    });
});