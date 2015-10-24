<?php 
//session启用
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
			'city_weather' => array(
				'object' => 'Weather',
				'method' => 'buildCityInfo',
				),
			'movie_find' => array(
				'object' => 'Movie',
				'method' => 'findMoive'
				)
		);

//调用follow.downFollow()取消关注
if($_POST['action']=="downFollow" )
{
	$f = new Follow();
	echo $f->downFollow($_POST['userID'],$_POST['movieID']);
}
//调用follow.upFollow()完成关注
if($_POST['action']=="upFollow" )
{
	$f = new Follow();
	echo $f->upFollow($_POST['userID'],$_POST['movieID']);
}



/*
 * modal只有text方法，不能解析为html，所以只能显示出html语句，所以弃用
	//请求电影信息
	if($_POST['action']=='movie_find')
	{	
		$use_array = $actions[$_POST['action']];
		$obj = new $use_array['object']();
		$arr = $obj->$use_array['method']($_POST['title']);
		echo '<div>
		<h2><?php echo $arr[0]["title"]; ?></h2></div>';
	}
*/

	//判断是否登陆，未登陆且请求登陆则跳转登陆
	//或者未请求且请求城市信息
	if(!isset($_SESSION['user']) && ($_POST['action']=="login" 
									|| $_POST['action']=="city_weather"))
	{
	echo '<div id="content">
	<form action="assets/inc/process.inc.php" method="post">
		<fieldset>
			<legend>
				Please Log In
			</legend>
			<label for="uname">Username</label>
			<input type="text" name="uname" id="uname" value="" />
			<label for="pword">Password</label>
			<input type="password" name="pword" id="pword" value="" />
			<input type="hidden" name="token" value="'.$_SESSION['token'].'" />
			<input type="hidden" name="action" value="user_login">
			<input type="submit" name="login_submit" value="Log In" />
			or <a href="./">cancel</a>
		</fieldset>
	</form>
</div>';
	}

	//判断请求注册则跳转注册
	elseif($_POST['action']=="register"){
	echo '<div id="content">
	<form action="assets/inc/process.inc.php" method="post">
		<fieldset>
			<legend>
				Welcome!!
			</legend>
			<label for="uname">Username</label>
			<input type="text" name="uname" id="uname" value="" />
			<label for="pword">Password</label>
			<input type="password" name="pword" id="pword" value="" />
			<label for="uemail">Useremail</label>
			<input type="text" name="uemail" id="uemail" value="" />
			<label for="ucity">Usercity</label>
			<input type="text" name="ucity" id="ucity" value="" />
			<input type="hidden" name="token" value="'.$_SESSION['token'].'" />
			<input type="hidden" name="action" value="user_register">
			<input type="submit" name="register_submit" value="Reg" />
			or <a href="./">cancel</a>
		</fieldset>
	</form>
</div>';
	}

	/*
	 * 保证session中的防御站标记与提交过来的标记一致及请求action合法（关联数组中）
	 */
	elseif(isset($actions[$_POST['action']]))
	{
		if (isset($_POST['city'])) {
			$use_array = $actions[$_POST['action']];
			$obj = new $use_array['object']($dbo=NULL,$_POST['city']);
			$arr = $obj->$use_array['method']();
			echo "<h6>".$arr[0]->city."</h6><ul><li>温度："
						.$arr[0]->temp."</li><li>风向："
						.$arr[0]->windfrom."</li><li>风级："
						.$arr[0]->winddegree."</li><li>湿度："
						.$arr[0]->dampness."</li><li>现场情况："
						.$arr[0]->njd."</li><li>气压："
						.$arr[0]->qy."</li><li>更新时间："
						.$arr[0]->updatetime."</li></ul>";
			}
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