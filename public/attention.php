<?php 
	//包含必要的配置信息
include_once '../sys/core/init.inc.php';
//如果未登陆且请求查看粉丝则跳回登陆
if(!isset($_SESSION['user']) && !empty($_GET['id'])){
	header("Location:./login.php");
	exit;
}

	      //attention
    $aa = new Attention();

        //User (admin)
    $ad = new Admin();

if(!empty($_GET['id'])){
	$id = $_GET['id'];
	//获取他的关注
	$uu = $aa->getAttenedUser($id);
	$page_title = $ad->findUserByID($id)['user_name']."的关注";
}
elseif(!empty($_GET['c'])){
	$id = $_SESSION['user']['id'];
	//获取我的粉丝
	$uu = $aa->getAttentionUser($id);
	$page_title = "关注我的";
}
else{
	$id = $_SESSION['user']['id'];
	//获取我的关注
	$uu = $aa->getAttenedUser($id);
	$page_title = "我的关注";
}

	  /*
	   * 页面初始化信息，包括标题，js和css
	   */
	  $js_files = array("init.js","movie_view.js");

	  $css_files = array("ajax.css");

	  include_once 'assets/common/header.inc.php';
 ?>
<br />
<br />
<br />
<div >
<br />
<label><?php echo $page_title ?></label>
  <?php foreach ($uu as $u): ?>
  	<!-- 保证自己不出现在自己的关注列表中 -->
  	<?php if($u['user_id'] != $id): ?>
    <div>
      <span><a href="javascript:void(0);" onclick="is_login(<?php echo (isset($_SESSION['user']) ? 'true' : 'false') ?>,<?php echo $u['user_id']; ?>)" ><span>
        <img src="<?php echo $u['user_img'] ?>" class="img-responsive img-thumbnail" style="width:100px;">
      </span> <?php echo $u['user_name'] ?></a></span>
    </div>
	<?php endif ?>
  <?php endforeach ?>
</div>
