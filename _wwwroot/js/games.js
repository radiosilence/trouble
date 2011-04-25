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
    $("#dialog-kill").dialog({
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
            $.get('/get/weapon_list', d, function(data) {
                var options = '';
                $('#weapon').html('<option value="0" class="dropdown_default">Select weapon used...</option>');
                $.each(data, function(k,v) {
                    options += '<option value="'+v['id']+'">'+v['name']+'</option>';
                });
                $('#weapon').html($('#weapon').html()+options);
            }, "json");
        }
    });
    $("#register_kill").click(function() {
        $("#dialog-kill").dialog("open");
    });

    $("#dialog-intel").dialog({
        autoOpen: false,
        height: 500,
        width: 450,
        modal: true,
        buttons: {
            "Buy Intel": function() {
                d = {
                    'game_id': $('#buy_intel').attr('game_id'),
                    'intel': $('select#intel').val(),
                    'subject': $('#buy_intel').attr('subject_id')
                };
                console.log(d);
                dialogResponse('/put/buy_intel', d, function(){
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
            $.get('/get/intel_list', d, function(data) {
                var options = '';
                $('#intel').html('<option value="0" class="dropdown_default">Select intel desired...</option>');
                $.each(data, function(k,v) {
                    options += '<option value="'+v['id']+'">'+v['name']+'</option>';
                });
                $('#intel').html($('#intel').html()+options);
            }, "json");
        }
    });

    $("#buy_intel").click(function() {
        $('#dialog-intel').dialog("open");
    })

    $("select").change(function () {
        var str = "";
        if($(this).val() != 0) {
            $(this).removeClass('default');
        } else {
            $(this).addClass('default');
        }
    });
    $('#intel').change(function() {
        $('#inteldescription').fadeOut(function() {
            $.post('/get/intel_description', {'field': $('#intel').val()}, function(data) {
                $('#inteldescription').text(data).fadeIn();
            });            
        });
    });
});