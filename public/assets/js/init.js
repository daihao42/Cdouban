//处理ajax的文件
var processFile = "assets/inc/ajax.inc.php";

$(function($){
	//维护模态窗口的功能函数
	var fx ={
		//检查模态窗口是否已存在，存在则返回该窗口，否则创建一新窗口并返回
		"initModal" : function(){
			//如果没有元素匹配，则长度属性等于0
			if ($("modal-window").length == 0) {
				return $("<div>")
								.hide()
								.addClass("modal-window")
								.appendTo("body");
			}
			else
			{
				//若已存在，则返回该模态窗口
				return $(".modal-window");
			}
		},
		//淡出此窗口并将其从DOM中删除
		"boxout" : function(event){
			//如果该函数用作某个元素的事件处理函数，那就在事件触发时阻止其默认行为
			if (event!=undefined) {
				event.preventDefault();
			}
			//从所有链接中删除active class
			$("a").removeClass("active");

			//淡出模态窗和覆盖层，然后将其从DOM中删除
			$(".modal-window,.modal-overlay")
				.fadeOut("slow",function(){
						$(this).remove();
				});
		},

		//将窗口添加到标记文件并让它淡入
		"boxin" : function(data, modal){
			//为页面创建一个覆盖层，并为其添加一个class和一个事件处理函数
			//然后将它追加到body元素中
			$("<div>").hide()
				.addClass("modal-overlay")
				.click(function(event){
					//删除活动
					fx.boxout(event);
				})
				.appendTo("body");

			//将数据载入模态窗口并将它追加到body元素
			modal.hide()
				.append(data)
				.appendTo("body");

			//淡入模态窗口和覆盖层
			$(".modal-window,.modal-overlay")
				.fadeIn("slow");
		}

	};

		

	$("[href='./login.php']").on("click",function(event){
		//阻止链接载入view.php
		event.preventDefault();

		//为链接添加activeclass
		$(this).addClass("active");

		//检查或创建模态窗口，选中它
		modal = fx.initModal();

		//创建关闭窗口的按钮
		$("<a>").attr("href","#")
				.addClass("modal-close-btn")
				.html("&times;")
				.click(function(event){
					//阻止默认行为
					//event.preventDefault();
					//删除模态窗
					//$(".modal-window")
					//	.remove();
					fx.boxout(event);
				})
				.appendTo(modal);
		//ajax处理模态框
		$.ajax({
			type: "POST",
			url: processFile,
			data: "action=login",
			success: function(data){
				//modal.append(data);
				fx.boxin(data,modal);
			},
			error: function(msg){
				modal.append(msg);
			}
		});
	});


	$("[href='./register.php']").on("click",function(event){
		//阻止链接载入view.php
		event.preventDefault();

		//为链接添加activeclass
		$(this).addClass("active");

		//检查或创建模态窗口，选中它
		modal = fx.initModal();

		//创建关闭窗口的按钮
		$("<a>").attr("href","#")
				.addClass("modal-close-btn")
				.html("&times;")
				.click(function(event){
					//阻止默认行为
					//event.preventDefault();
					//删除模态窗
					//$(".modal-window")
					//	.remove();
					fx.boxout(event);
				})
				.appendTo(modal);
		//ajax处理模态框
		$.ajax({
			type: "POST",
			url: processFile,
			data: "action=register",
			success: function(data){
				//modal.append(data);
				fx.boxin(data,modal);
			},
			error: function(msg){
				modal.append(msg);
			}
		});
	});

	//点击时将活动信息在模态窗口中显示出来
	$("h6").on("click","a",function(event){
		//事件处理脚本

		//阻止链接载入view.php
		event.preventDefault();

		//为链接添加activeclass
		$(this).addClass("active");

		//从链接href属性得到查询字符
		var data = $(this).attr("href").replace(/.+?\?(.*)$/,"$1");

		//检查或创建模态窗口，选中它
		modal = fx.initModal();

		//创建关闭窗口的按钮
		$("<a>").attr("href","#")
				.addClass("modal-close-btn")
				.html("&times;")
				.click(function(event){
					//阻止默认行为
					//event.preventDefault();
					//删除模态窗
					//$(".modal-window")
					//	.remove();
					fx.boxout(event);
				})
				.appendTo(modal);

		//ajax处理模态框
		$.ajax({
			type: "POST",
			url: processFile,
			data: "action=city_weather&"+data,
			success: function(data){
				//modal.append(data);
				fx.boxin(data,modal);
			},
			error: function(msg){
				modal.append(msg);
			}
		});

	});


/* modal只有text方法，不能解析为html，所以只能显示出html语句，所以弃用

	//为图片打开ajax链接
	$(".thumbnail").on("click","a",function(event){
		//事件处理脚本

		//阻止链接载入view.php
		event.preventDefault();

		//为链接添加activeclass
		$(this).addClass("active");

		//从链接href属性得到查询字符
		var data = $(this).attr("href").replace(/.+?\?(.*)$/,"$1");

				//ajax处理模态框
				
		$.ajax({
			type: "POST",
			url: processFile,
			data: "action=movie_find&"+data,
			success: function(data){
				$('#myModal').find('.modal-body').text(data);
				$('#myModal').modal('show');
			},
			error: function(msg){
				//modal.append(msg);
			}
		});
	});
*/
 
});

   //ajax处理用户关注影片事件
  //完成php调用，并将关注图标变红
  function follow(userID,movieID,obj)
  { 
  	//userID为0表示为登陆
    //如果已关注，则取消关注
    if(obj.style.color == "rgb(219, 112, 147)"){
    	if(userID!=0){
    //ajax调用follow.downFollow()取消关注
    		$.ajax({
			type: "POST",
			url: processFile,
			data: "action=downFollow&userID="+userID+"&movieID="+movieID,
			success: function(data){
				//do something fun
			}
		});
    		}
    //完成后将星形变黑
    obj.style.color="black";
    }
    //如果未关注(color:red)，则关注
    else
    {
    	if(userID!=0){
    //调用follow.upFollow()完成关注
    $.ajax({
			type: "POST",
			url: processFile,
			data: "action=upFollow&userID="+userID+"&movieID="+movieID,
			success: function(data){
				//do something fun
			}
		});
    }
    //完成后将星形变红
    obj.style.color="rgb(219, 112, 147)";
    }
  }