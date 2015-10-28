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

<br />
<br />
<br />
<div class="container">
	<form class="form-vertical" action="assets/inc/process.inc.php" method="post">
	<!--
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
		-->
		<div class="form-group">
          <label class="fa-label">
              <i class="fa fa-envelope"></i>
            </label>
          <label class="control-label">电子邮箱</label>
          <div class="controls">
            <input class="form-control" type="text" name="uemail" id="uemail" required placeholder='邮箱' />
            <div class="help-block"></div>
          </div>
        </div>

        <div class="form-group">
          <label class="fa-label">
              <i class="fa fa-user"></i>
            </label>
          <label class="control-label">用户名</label>
          <div class="controls">
            <input class="form-control" type="text" name="uname" id="uname" required placeholder='用户名' />
            <div class="help-block"></div>
          </div>
        </div>

        <div class="form-group">
            <label class="fa-label">
              <i class="fa fa-unlock-alt"></i>
            </label>
          <label class="control-label">密码</label>
          <div class="controls">
            <input class="form-control" type="password" name="pword" id="pword" required placeholder='密码'/>
          </div>
        </div>

        <div class="form-group">
          <label class="fa-label">
              <i class="fa fa-car "></i>
            </label>
          <label class="control-label">城市</label>
          <div class="controls">
            <input class="form-control" type="text" name="ucity" id="ucity" required placeholder='城市' />
            <div class="help-block"></div>
          </div>
        </div>

        <div class="form-group">
        	<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="user_register">
          	<input class="login-btn btn-primary btn" type="submit" name="register_submit" value="注册" />
          	or <a href="./">取消</a>
          </div>
      </form>
	</form>
</div>

<?php 
/*
 * 输出页尾
 */
	include_once 'assets/common/footer.inc.php';
 ?>