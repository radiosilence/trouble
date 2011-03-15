$(function() {
    $("#tabs").tabs();
    $("#accordion").accordion({
        autoHeight: false,
        navigation: true
    });
});
$(function(){   
    var uploader = new qq.FileUploader({
        element: document.getElementById('imagefile-uploader'),
        action: 'put/save_agent_image',
        onComplete: function(id, fileName, responseJSON){
          var fn = responseJSON['filename']+'.'+responseJSON['ext'];
          $('#imagefile_img').attr('src','img/agent/'+fn);
          $('#imagefile_img').css('display','block');
          $('#imagefile').val(fn);
        },
        params: {
          tok: $.cookie('tok')
        }
    });           
});    