$(function(){
  // Dialog     
    $('#dialog').dialog({
      autoOpen: false,
      modal: true,
      width: 600,
      buttons: {
        "Ok": function() { 
          $(this).dialog("close"); 
        }
      }
    });
   $('a.join_game').click(function(){
        d = {
           'id': ($(this).attr('game_id')),
           'tok': $.cookie('tok')
        };
        $.post('/put/join_game', d, function(data) {
            $('#dialog').text(data.message);
            $('#dialog').dialog('option', 'title', data.status);
            $('#dialog').dialog('open');
        }, "json");
        return false;
   });
 });