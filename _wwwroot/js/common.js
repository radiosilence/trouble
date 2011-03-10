function dialogResponse(url, d, success, fail) {
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
    d['tok'] = $.cookie('tok');
    status = $.post(url, d, function(data) {
        $('#dialog').text(data.message);
        $('#dialog').dialog('option', 'title', data.status);
        $('#dialog').dialog('open');
        if(data.status == 'Success') {
            if(success != undefined) {
                success(data.message);
            }
        } else {
            if(fail != undefined) {
                fail(data.message);            
            }
        }
    }, "json");
    return false;
}

function dialogConfirm(title, text, callback, callbackData) {
    $('#dialog').text(text);    
    $("#dialog").dialog({
        resizable: false,
        height:140,
        width: 600,
        modal: true,
        title: title,
        buttons: {
            Ok: function() {
                callback(callbackData);
            },
            Cancel: function() {
                $(this).dialog("close");
          }
        }
    });
    $('#dialog').dialog('open');
}

$(function(){
});

$(function(){
   $('#button_login').click(function(){
        return dialogResponse('/login',{
          'username': $('#login_username').val(),
          'password': $('#login_password').val() 
        }, function(){
            location.reload();
        });
   });
});