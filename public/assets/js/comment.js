$(document).ready(function(){
	//do sth fun
});
//ajax获取增加的回复节点
//onclick在前，Q.click()在后
//页面生成逻辑放入ajax.inc.php页面，可附带php语句
function addReply(id)
{
	var rep = "#"+id+"rep";
	var repid = "#"+id+"reply";
	var obj = $(rep);
	//如果按钮为回复，并未展开时获取生成DOM
	if(obj.text()=='回复')
	{
		//添加回复楼层
		var par = obj.parent();
		var reply = $("<div></div>");
		//ajax获取回复
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
						//一开始隐藏
						reply.hide();
						//将按钮置为“收起”
						obj.html('收起<i class="glyphicon glyphicon-chevron-up"></i>');
						$(repid).toggle(1000);
						}
					});
		}
	else if(obj.text()=='收起')
			{
			//删除回复楼层
			$(repid).toggle(1000,function(){
				$(this).remove();
			});
			//将按钮置为“收起”
			obj.html('回复<i class="glyphicon glyphicon-chevron-down"></i>');
			}
}

//处理回复某人的回复
//param int commentID 评论的ID
//param int id 某人的ID
//param string name 某人的名
//记住！！！js传字符串参数需要加单引号''!!!
function repToRep(commentID,id,name)
{
	var inp = "input#rep"+commentID;
	var par = $(inp).parent();
	//添加input域
	//alert(par.attr("class"));
	var button = '<input type="hidden" name="to_who" value="'+id+'">';
	par.append(button);
	//将回复input的placeholder置“回复某人”
	var ah = "回复"+name+":";
	//alert(name);
	$(inp).attr("placeholder",ah);
}


