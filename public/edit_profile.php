<?php 
//包含必要的配置信息
include_once '../sys/core/init.inc.php';
if(!isset($_SESSION['user']) || ($_SESSION['user']['id']!=$_GET['id'])){
	header("Location:./login.php");
	exit;
}
	  /*
	   * 页面初始化信息，包括标题，js和css
	   */
	  $page_title = $_SESSION['user']['name']."的资料";

	  $js_files = array("upload.js","dropzone.min.js");

	  $css_files = array("ajax.css","dropzone.min.css","edit_profile.css");

    //User (admin)
    $uu = new Admin();

    //getUserInfo
    $ii = $uu->findUserByID($_GET['id']);

    include_once 'assets/common/header.inc.php';
 ?>
<br />
<br />
<br />
<div class="container">

	<div class="headimg">
	<div><label>更换头像</label></div>	
		<div class="now_img">
		<img src="<?php echo $ii['user_img'] ?>"  alt="userimg" class="img-responsive img-circle">
		</div>
		<div class="upload_img">
		<form action="assets/inc/upload.inc.php" enctype="multipart/form-data" class="dropzone" id="dropz"></form>
		</div>
	</div>
<div class="form_update">
<form class="form-vertical" action="assets/inc/process.inc.php" method="post">
        <div class="form-group">
          <label class="fa-label">
              <i class="fa fa-user"></i>
            </label>
          <label class="control-label">用户名</label>
          <div class="controls">
            <input class="form-control" type="text" name="uname" required value='<?php echo $ii['user_name'] ?>' />
            <div class="help-block"></div>
          </div>
        </div>

        <div class="form-group">
            <label class="fa-label">
              <i class="fa fa-car"></i>
            </label>
          <label class="control-label">城市</label>
          <div class="controls">
            <input class="form-control" type="text" name="ucity" required value='<?php echo $ii['user_city'] ?>'/>
          </div>
        </div>

        <div class="form-group">
          <label class="fa-label">
              <i class="fa fa-comment-o"></i>
            </label>
          <label class="control-label">个性签名</label>
          <div class="controls">
             <input class="form-control" type="text" name="uabout" value='<?php echo $ii['user_about'] ?>'/>
          </div>
        </div>

        <div class="form-group">
        <!--头像默认为原头像 ,上传成功后回调函数将value改为需要值-->
        	<input type="hidden" name="uimg" id="uimg" value="<?php echo $ii['user_img'] ?>" />
        	<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="user_editprofile">
			<input type="hidden" name="uid" value="<?php echo $_GET['id'] ?>">
          <input class="login-btn btn-primary btn" type="submit" value="确认" />
          </div>
	</form>
	</div>
</div>
 <?php include_once 'assets/common/footer.inc.php'; ?>