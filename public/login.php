<?php

//包含必要的配置信息
	  include_once '../sys/core/init.inc.php';

/*
 * 输出页头
 */
	  $page_title = 'Login';
	  $css_files = array();
	  $js_files = array();

	  include_once 'assets/common/header.inc.php';
?>

<div id="content">
	<form action="assets/inc/process.inc.php" method="post">
		<fieldset>
			<legend>
				Please Log In
			</legend>
			<label for="uname">UserE-mail</label>
			<input type="text" name="uemail" id="uemail" value="" />
			<label for="pword">Password</label>
			<input type="password" name="pword" id="pword" value="" />
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="user_login">
			<input type="submit" name="login_submit" value="Log In" />
			or <a href="./">cancel</a>
		</fieldset>
	</form>
</div>

<?php 
/*
 * 输出页尾
 */
	include_once 'assets/common/footer.inc.php';
 ?>