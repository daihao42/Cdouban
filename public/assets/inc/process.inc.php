<?php
/*
 * 启用session
 */
session_start();

/*
 * 包含初始化文件
 */
	include_once '../../../sys/config/db-cred.inc.php';
	//foreach读出配置常量并定义
	foreach($C as $name => $val){
		define($name, $val);
	}



	/*
	 * 以表单action为键生成一个关联数组（查找表）
	 */
	$actions = array(
			'user_login' => array(
				'object' => 'Admin',
				'method' => 'processLoginForm',
				'header' => 'Location:../../'
				),
			'user_logout' => array(
				'object' => 'Admin',
				'method' => 'processLogout',
				'header' => 'Location:../../'
				),
			'user_register' => array(
				'object' => 'Admin',
				'method' => 'processRegister',
				'header' => 'Location:../../'
				),
			'user_editprofile' => array(
				'object' => 'Admin',
				'method' => 'updateUserInfo',
				'header' => 'Location:../../'
				)
		);

	/*
	 * 保证session中的防御站标记与提交过来的标记一致及请求action合法（关联数组中）
	 */
	if($_POST['token'] == $_SESSION['token']
		&& isset($actions[$_POST['action']]))
	{
		$use_array = $actions[$_POST['action']];
		$obj = new $use_array['object']($dbo);
		if( TRUE === $msg=$obj->$use_array['method']())
		{
			header($use_array['header']);
			exit;
		}
		else
		{
			//如果出错，输出错误信息并退出
			die($msg);
		}
	}
	else
	{
		//如果token/action非法，重定向到主页
		header("Location:../../");
		exit;
	}

	function __autoload($class_name)
	{
		$filename = '../../../sys/class/class.'
			.strtolower($class_name).'.inc.php';
		if(file_exists($filename))
		{
			include_once $filename;
		}
	}
	
?>