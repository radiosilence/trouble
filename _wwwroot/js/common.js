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
        message = '<p>'+data.message+'</p>';
        $.each(data['errors'], function(k, error) {
            message += '<div class="ui-state-error ui-corner-all response_error" ><span class="ui-icon ui-icon-alert"></span>'+error+'</div>';
        });
        $('#dialog').html(message);
        $('#dialog').dialog('option', 'title', data.status);
        $('#dialog').dialog('open');
        if(data.status == 'Success') {
            if(success != undefined) {
                success(data.message);
            }
        } else if(data.status == 'Fail') {
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
        height: 140,
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
    $('div.tabs').tabs({
        cookie: {}
    });
});

$(function(){
   $('button#login').click(function(){
        return dialogResponse('/login', {
          'username': $('#login_username').val(),
          'password': $('#login_password').val() 
        }, function(){
            location.reload();
        });
   });
   $('button#logout').click(function(){
        return dialogResponse('/logout', {'x':'x'}, function(){
            location.reload();
        });
   });
});