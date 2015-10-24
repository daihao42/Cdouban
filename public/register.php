<?php

//包含必要的配置信息
	  include_once '../sys/core/init.inc.php';

/*
 * 输出页头
 */
	  $page_title = 'Register';
	  $css_files = array();
	  $js_files = array();

	  include_once 'assets/common/header.inc.php';
?>

<div id="content">
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
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="user_register">
			<input type="submit" name="register_submit" value="Reg" />
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