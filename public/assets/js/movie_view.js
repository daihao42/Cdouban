function is_login (login,id) {
	//登陆则转到用户中心
	if(login){
	var url = "./user_view.php?id="+id;
	location.href=url;
	}
	// 未登陆则弹出警告框
	else{
	$('#warn_login').modal('show');
	}

};
//在评论提交前转义一下\n为<br />
function n2br(){
	//使用pre标签后可以自动对\n和空格转义所以不需要了
	//可以do sth fun
	var str = $(".form-control").val();
	str=str.replace(/\n/ig,"<br/>");
	$(".form-control").val(str);
	//return false 则不提交
	//return false;
	return true;
};

//ajax保存回复
function reply(id)
{
	var rep = "#"+id+"reply";
	var inp = "input#rep"+id;
	var rep_f = "#reply-f"+id;
	//输入为空
	if($(inp).val()=="")
	{
		//显示警告框
		$('#warn_input').modal('show');
		//使input获得焦点
		$(inp).focus();
	}
	//不为空则提交输入
	else{
		$.ajax({
			type: "POST",
			url: "assets/inc/ajax.inc.php",
			data: $(rep_f).serialize(),
			success: function(data){
					//do something fun
					//添加成功
					//重新刷新回复框
					/*
					if(data){
						//将回复显示
						$(rep).prepend("<hr />");
						$(rep).prepend($("<p></p>").text(data));
						//清空输入框
						$(inp).val("");
					}
					*/
					var repi = "#"+id+"rep";
					var repid = "#"+id+"reply";
					var obj = $(repi);
					$(repid).remove();
					var par = obj.parent();
					var reply = $("<div></div>");
					$.ajax({
				type: "POST",
				url: "assets/inc/ajax.inc.php",
				data: "action=getReply&commentID="+id,
				success: function(data){
						//do something fun
						//回复及输入框
						reply.html(data);
						reply.attr('id',id+"reply");
						//加边框
						//reply.attr('style',"border:1px solid #aaa;")
						par.append(reply);
						}
					});
				}
		});
	}
	return true;
}