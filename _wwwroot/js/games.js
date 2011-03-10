$(function(){
    $('button.join_game').click(function(){
        return dialogResponse('/put/join_game', {
            'id': $(this).attr('game_id')
        }, function(){
            location.reload();
        });
    });
});