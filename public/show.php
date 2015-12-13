<?php 
	//包含必要的配置信息
include_once '../sys/core/init.inc.php';

	  $js_files = array("movie_view.js","show.js");

	  $css_files = array("show.css");

	if(isset($_SESSION['user']))
	{
		$page_title = "个人动态";
		$re = new Recommend($_SESSION['user']['id'],0);
		$res = $re->getNews();
	}
	else
	{
		$page_title = "热门动态";
		$re = new Recommend();
		$res = $re->getNews();
	}

	$movie = new Movie();

	$admin = new Admin();

	  include_once 'assets/common/header.inc.php';

 ?>
<br />
<br />
<br />
 <div class="container">
 <div class="center_show">
 <ul class="list-group">
<?php foreach ($res as $i): ?>

	<?php if($i['type'] == 'ac'): ?>
		<?php $uu = $admin->findUserByID($i['author_id']) ?>
		<?php $mm = $movie->findMoiveByID($i['movie_id']) ?>
	<div class="alert alert-success" role="alert">
	<li class="list-group-item">
		<p><a href="javascript:void(0);" onclick="is_login(<?php echo (isset($_SESSION['user']) ? 'true' : 'false') ?>,<?php echo $uu['user_id']; ?>)">
			<?php echo $uu['user_name'] ?></a> 评论 
			<a href="<?php echo './movie_view.php?movie='.$mm[0]['title'] ?>"><?php echo $mm[0]['title'] ?></a>:
		<span style="float:right;"><?php echo $i['time'] ?></span></p>
		<p><?php echo $i['content'] ?><a href="<?php echo './movie_view.php?movie='.$mm[0]['title'].'#'.$i['id'].'rep' ?>">查看</a></p>
		</li>
	</div>

	<?php elseif($i['type'] == 'fc'): ?>
		<?php $uu = $admin->findUserByID($i['author_id']) ?>
		<?php $mm = $movie->findMoiveByID($i['movie_id']) ?>
	<div class="alert alert-warning" role="alert">
	<li class="list-group-item">
		<p><a href="<?php echo './movie_view.php?movie='.$mm[0]['title'] ?>"><?php echo $mm[0]['title'] ?></a>有新评论:
		<span style="float:right;"><?php echo $i['time'] ?></span></p>
		<p><?php echo $i['content'] ?><a href="<?php echo './movie_view.php?movie='.$mm[0]['title'].'#'.$i['id'].'rep' ?>">查看</a></p>
		</li>
	</div>

	<?php elseif($i['type'] == 'af'): ?>
		<?php $uu = $admin->findUserByID($i['user_id']) ?>
		<?php $mm = $movie->findMoiveByID($i['movie_id']) ?>
	<div class="alert alert-info" role="alert">
	<li class="list-group-item">
		<p><a href="javascript:void(0);" onclick="is_login(<?php echo (isset($_SESSION['user']) ? 'true' : 'false') ?>,<?php echo $uu['user_id']; ?>)">
			<?php echo $uu['user_name'] ?></a> 收藏了
			<a href="<?php echo './movie_view.php?movie='.$mm[0]['title'] ?>"><?php echo $mm[0]['title'] ?></a>
		<span style="float:right;"><?php echo $i['time'] ?></span></p>
		</li>
	</div>

	<?php endif ?>

<?php endforeach ?>
</ul>


<!-- 未登陆则显示警告模态框 -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="warn_login">
  <div class="modal-dialog modal-sm">
      <div class="alert alert-danger" role="alert">
      <h5 align="center"><strong>警告：</strong></h5>
      <p align="center">你需要登录先！</p> </div>
  </div>
</div>
</div>
<!-- 加载更多 -->
<div class="well well-sm" style="text-align:center;color:gray;">
	<button onclick="callmore()">加载更多<i class="fa fa-spinner fa-spin fa-2x" style="display:none;"></i>
	</button> 
</div>
 </div>
<?php include_once 'assets/common/footer.inc.php'; ?>