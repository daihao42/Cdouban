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

}