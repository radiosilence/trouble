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
});