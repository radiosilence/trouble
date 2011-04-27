$(function() {
    $("div.tabs").tabs({
        cookie: {}
    });
    $("div.accordion").accordion({
        autoHeight: false,
        navigation: true
    });

    $('#submit_agent_form').click(function(data) {
        var d = {};
        $.each($('#agent_form').serializeArray(), function(i, field) {
            d[field.name] = field.value;
        });
        dialogResponse('/put/save_agent', d, function(){
            location.reload();
        });
        return false;
    });
});
$(function(){   
    var uploader = new qq.FileUploader({
        element: $('#imagefile-uploader')[0],
        action: 'put/save_image',
        onComplete: function(id, fileName, responseJSON){
          var fn = responseJSON['filename']+'.'+responseJSON['ext'];
          $('#imagefile_img').attr('src','img/agent/'+fn);
          $('#imagefile_img').css('display','block');
          $('#imagefile').val(fn);
        },
        params: {
          _tok: $.cookie('tok'),
          type: "photo"
        }
    });
    var uploader2 = new qq.FileUploader({
        element: $('#avafile-uploader')[0],
        action: 'put/save_image',
        onComplete: function(id, fileName, responseJSON){
          var fn = responseJSON['filename']+'.'+responseJSON['ext'];
          $('#avafile_img').attr('src','img/avatar/'+fn);
          $('#avafile_img').css('display','block');
          $('#avafile').val(fn);
        },
        params: {
          _tok: $.cookie('tok'),
          type: "avatar"
        }
    });
});    