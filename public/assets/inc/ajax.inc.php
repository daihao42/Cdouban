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

//*****************changeSession['type']**************//
if($_POST['action'] == "addType")
{
	//echo $_POST['action'];
	$cho = $_POST['cho'];
	$val = $_POST['val'];
//type允许多类型
	if($cho == 'type'){
		if($_POST['val'] == '全部')
	{
		//选择全部，清空类型
		unset($_SESSION[$cho]);
		$_SESSION[$cho][0] = '全部';
	}
	else
	{
		if($_SESSION[$cho][0] == '全部')
		{
			unset($_SESSION[$cho]);
		}
		//如果已经选中筛选，则取消筛选
		if(in_array($_POST['val'],$_SESSION[$cho],True))
		{
			$key = array_search($_POST['val'],$_SESSION[$cho]);
			if ($key !== false)
    			array_splice($_SESSION[$cho], $key, 1);
		}
		//不存在，则添加筛选
		else
		{
			$_SESSION[$cho][] = $_POST['val'];
			$_SESSION[$cho] = array_unique($_SESSION[$cho]);
		}
	}
	}
	//ontimes和country不允许多选
	else
	{
		$_SESSION[$cho][0] = $_POST['val'];
	}
	//echo $_POST['type'];
}
//*****************^^^^^^^^changeSession['type']**************//

//******************getNews()********************//
if ($_POST['action'] == "getNews") {
	//sleep(2);

	$movie = new Movie();
	$admin = new Admin();

	if(isset($_SESSION['user']))
	{
		$re = new Recommend($_SESSION['user']['id'],$_POST['pagecnt']);
		$is_login = 'true';
	}
	else
	{
		$re = new Recommend(NULL,$_POST['pagecnt']);
		$is_login = 'false';
	}

//查询时间是否已经溢出
	if($re->overTimeFlow()){

	$res = $re->getNews();

	foreach ($res as $i) {
		$mm = $movie->findMoiveByID($i['movie_id']);
		if($i['type'] == 'ac'){
			$uu = $admin->findUserByID($i['author_id']);
				echo <<<HTML
	<div class="alert alert-success" role="alert">
	<li class="list-group-item">
		<p><a href="javascript:void(0);" onclick="is_login({$is_login},{$uu['user_id']})">
			{$uu['user_name']}</a> 评论 
			<a href="./movie_view.php?movie={$mm[0]['title']}">{$mm[0]['title']}</a>:
		<span style="float:right;">{$i['time']}</span></p>
		<p>{$i['content']}<a href="./movie_view.php?movie={$mm[0]['title']}#{$i['id']}rep">查看</a></p>
		</li>
	</div>
HTML;
		}
		elseif($i['type'] == 'fc')
		{
			$uu = $admin->findUserByID($i['author_id']);
			echo <<<HTML
	<div class="alert alert-warning" role="alert">
	<li class="list-group-item">
		<p><a href="./movie_view.php?movie={$mm[0]['title']}">{$mm[0]['title']}</a>有新评论:
		<span style="float:right;">{$i['time']}</span></p>
		<p>{$i['content']}<a href="./movie_view.php?movie={$mm[0]['title']}#{$i['id']}rep">查看</a></p>
		</li>
	</div>
HTML;
		}
		elseif($i['type'] == 'af')
		{
			$uu = $admin->findUserByID($i['user_id']);
			echo <<<HTML
	<div class="alert alert-info" role="alert">
	<li class="list-group-item">
		<p><a href="javascript:void(0);" onclick="is_login($is_login,{$uu['user_id']})">
			{$uu['user_name']}</a> 收藏了
			<a href="./movie_view.php?movie={$mm[0]['title']}">{$mm[0]['title']}</a>
		<span style="float:right;">{$i['time']}</span></p>
		</li>
	</div>
HTML;
		}
	}
  }
  //时间溢出，返回false
  else
  {
  	echo 'false';
  }
}


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

//Attention
$atten = new Attention();
//调用attention.upAttention方法,并判断是否互相关注
if ($_POST['action']=="upAttention") 
{
	if($atten->upAttention($_POST['attenID'],$_POST['attenedID'])
					&& $atten->isAttention($_POST['attenedID'],$_POST['attenID']))
	{
		echo '互相关注';
	}
else{
	echo "已关注";
}
}

//调用attention.downAttention方法,并判断是否互相关注
if ($_POST['action']=="downAttention") 
{
	if($atten->downAttention($_POST['attenID'],$_POST['attenedID']))
	{
		echo '关注';
	}
else{
	echo FALSE;
}
}


//取得回复
if ($_POST['action']=="getReply") {
	$rr = new Reply();
	$aa = new Admin();
	$re = $rr->getReplyByComment($_POST['commentID']);
	$res = "";
	foreach ($re as $i) {
		$uu = $aa->findUserByID($i['author_id']);
		$is_l = (isset($_SESSION['user']) ? 'true' : 'false');
		//如果是回复回复的回复
		if($i['to_who'] != 0){
			$to_uu = $aa->findUserByID($i['to_who']);
			$con = <<<HTML
		<div class="alert alert-warning" role="alert">
		<a href="javascript:void(0);" onclick="is_login({$is_l},{$uu['user_id']} )">{$uu['user_name']}</a>
		对 <a href="javascript:void(0);" onclick="is_login({$is_l},{$to_uu['user_id']} )">{$to_uu['user_name']}</a>说：
		<span style="float:right"><a href="javascript:void(0);" onclick="repToRep({$i['to_comment']},{$uu['user_id']},'{$uu['user_name']}')">回复</a>{$uu['time']}</span>
		<p>&nbsp;&nbsp;{$i['content']}</p>
		</div>
HTML;
		}
		//回复评论的回复
		else{
		$con = <<<HTML
		<div class="alert alert-warning" role="alert">
		<a href="javascript:void(0);" onclick="is_login({$is_l},{$uu['user_id']} )">{$uu['user_name']}</a>说：
		<span style="float:right"><a href="javascript:void(0);" onclick="repToRep({$i['to_comment']},{$uu['user_id']},'{$uu['user_name']}')">回复</a>{$uu['time']}</span>
		<p>&nbsp;&nbsp;{$i['content']}</p>
		</div>
HTML;
		}
		$res = $res.$con;
	}

	//如果登陆了，就显示回复框，否则不显示
	if(isset($_SESSION['user']))
	{
		//注意form中的onkeydown事件，禁止回车提交表格
		$inp = <<<HTML
 <div class="reply_forms">
 <form class="form-vertical" id="reply-f{$_POST['commentID']}" onkeydown="if(event.keyCode==13){return false;}">
        <div class="form-group">
          <div class="controls">
          <input name="content" class="form-control" id="rep{$_POST['commentID']}" placeholder="说点什么" />
          <input type="hidden" name="token" value="{$_SESSION['token']}" />
          <input type="hidden" name="author_id" value="{$_SESSION['user']['id']}">
			<input type="hidden" name="action" value="reply_post">
			<input type="hidden" name="to_comment" value="{$_POST['commentID']}">
			<input type="button" class="btn btn-primary" onclick="return reply({$_POST['commentID']});" value="回复"></input>
            <div class="help-block"></div>
          </div>
        </div>
	</form>
 </div>
HTML;
 	$res = $res.$inp;
 }
	echo $res;
}
//^^^取得回复
//添加回复
if($_POST['action']=='reply_post' && $_POST['token'] == $_SESSION['token'])
{
	$rr = new Reply();
	if($rr->addReply())
	{
		echo $_POST['content'];
	}
}
//^^^^^添加回复
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