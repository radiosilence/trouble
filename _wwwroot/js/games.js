$(function(){
    $('button.join_game').click(function(){
        return dialogResponse('/put/join_game', {
            'id': $(this).attr('game_id')
        }, function(){
            location.reload();
        });
    });

    $('button.leave_game').click(function(){
        data = {
            'id': $(this).attr('game_id')
        };
        dialogConfirm('Leave Game',
            'Are you sure you wish to leave this game?',
            function(d){
              console.log($(this).attr('game_id'));
                dialogResponse('/put/join_game', d, function(){
                    location.reload();
                });
            }, data);
        return false;
    })
});