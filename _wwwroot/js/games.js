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
        var game_id = $(this).attr('game_id');
        data = {
            'id': game_id
        };
        var type = $(this).attr('invite_only');

        $('#join-normal').hide();
        $('#join-voucher').hide();
        $('#join-password').hide();
        $('#dialog-join').attr('game_id', game_id);
        if(type == 0) {
            $('#join-normal').show();
        } else if(type == 1) {
            $('#join-password').show();
        } else if(type == 2) {
            $('#join-voucher').show();
        }
        $("#dialog-join").dialog("open");
        return false;
    });

    $("#dialog-join form").submit(function(e) {
        e.preventDefault();
    });
    $("#dialog-join").dialog({
        autoOpen: false,
        width: 450,
        modal: true,
        buttons: {
            "Join Game": function() {
                d = {
                    'voucher': $('input#voucher').val(),
                    'password': $('input#gamepass').val(),
                    'game_id': $('#dialog-join').attr('game_id')
                }
                dialogResponse('/put/join_game', d, function(){
                    window.location = '/game/'+$('#dialog-join').attr('game_id');
                });
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
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

    $("#dialog-kill form").submit(function(e) {
        e.preventDefault();
    });
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


    $("#dialog-intel form").submit(function(e) {
        e.preventDefault();
    });

    $("#dialog-intel").dialog({
        autoOpen: false,
        width: 450,
        modal: true,
        buttons: {
            "Buy Intel": function() {
                d = {
                    'game_id': $('#buy_intel').attr('game_id'),
                    'intel': $('select#intel').val(),
                    'subject': $('#buy_intel').attr('subject_id')
                };
                dialogResponse('/put/buy_intel', d, function(){
                        location.reload();
                });
            },
            Cancel: function() {
                $(this).dialog("close");
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
    });

    $("#dialog-redeem-credit form").submit(function(e) {
        e.preventDefault();
    });
    $("#dialog-redeem-credit").dialog({
       autoOpen: false,
       width: 450,
       modal: true,
       buttons: {
           "Redeem Voucher": function() {
               d = {
                   'game_id': $("#dialog-redeem-credit").attr('game_id'),
                   'voucher': $("#voucher-input").val()
               };
               dialogResponse('/put/redeem_credit', d, function(){
                   window.location = '/game/'+$('#dialog-redeem-credit').attr('game_id');
               });
           },
           Cancel: function() {
               $(this).dialog('close');
           }
       }
    });

    $("button.redeem_credit").click(function() {

        $("#dialog-redeem-credit")
            .attr('game_id', $(this).attr('game_id'))
            .dialog("open");
    })

    $('#intel').change(function() {
        $('#inteldescription').fadeOut(function() {
            $.post('/get/intel_description', {'field': $('#intel').val()}, function(data) {
                $('#inteldescription').text(data).fadeIn();
            });            
        });
    });

    $('#vouchers_submit').click(function(e) {
        e.preventDefault();
        number = $('#vouchers_number').val();
        type = $('#vouchers_type').val();
        game_id = $(this).attr('game_id');
        window.open('/game/'+game_id+'/generate-vouchers/'+number+'/'+type);
        console.log(number, type);
    });

    $('#vouchers_number').change(function() {
        $('#vouchers_numval').text($(this).val() + " vouchers / " + ($(this).val() / 14) + " page(s)");
    });

    $(".datepick").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('.timepick').timepicker();
});
