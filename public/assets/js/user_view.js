//ajax处理用户关注用户事件
//完成php调用，并将关注按钮变为default
  function attention(attenID,attenedID,obj)
  {
  	 if(obj.text=='关注')
  	 {
  	 	//ajax调用attention.upAttention()关注
    		$.ajax({
			type: "POST",
			url: "assets/inc/ajax.inc.php",
			data: "action=upAttention&attenID="+attenID+"&attenedID="+attenedID,
			success: function(data){
				//do something fun
				//改变button属性和内容
				obj.className="btn btn-default";
				obj.text = data;
			}
		});
  	 }
  	 else
  	 {
  	 	//ajax调用attention.downAttention()关注
    		$.ajax({
			type: "POST",
			url: "assets/inc/ajax.inc.php",
			data: "action=downAttention&attenID="+attenID+"&attenedID="+attenedID,
			success: function(data){
				//do something fun
				//改变button属性和内容
				if(data){
				obj.className="btn btn-primary";
				obj.text = data;
			}
			}
		});
  	 }
  }