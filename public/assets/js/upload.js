$(function($){

	Dropzone.options.dropz = {
    maxFilesize: 1.2, // MB
    acceptedFiles: ".jpg,.gif,.png",
    maxFiles: 1,
    //第一个参数为object file，第二个参数为服务器响应
    success: function(file,data){
    	//将input域的uimg的value换为上传图片的返回路径
    	var img_src = "../static/headimg/"+data;
    	$("#uimg").val(img_src);
    }
    }

});
