 $(document).ready(function(){
   $('a.join_game').click(function(){
        d = {
           'id': ($(this).attr('game_id')),
           'tok': $.cookie('tok')
        };
        $.post('/put/join_game', d, function(data) {
            console.log(data);
        });
        return false;
   });
 });