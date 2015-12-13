var cont = 1;
$(window).scroll(function(){  
    // 当滚动到最底部以上n像素时， 加载新内容  
    if ($(document).height() - $(this).scrollTop() - $(this).height()<1) {  
  	callmore();
    } 
}); 

function callmore()
{
	   	$(".fa-spinner").show();
    	$.ajax({
			type: "POST",
			url: "assets/inc/ajax.inc.php",
			data: "action=getNews&pagecnt="+cont,
			success: function(data){
				//时间溢出，隐藏加载更多
				if(data == 'false')
				{
					$(".well-sm").hide();
				}
				//时间未溢出，添加数据
				else{
					$(".list-group").append(data);
					$(".fa-spinner").hide();
			    	//alert(cont);
    				cont++;
    			}
			}
    	});
}