<?php 

//包含必要的配置信息
include_once '../sys/core/init.inc.php';
//如果未登陆或者没有查询影片，则调回主页
//2015-10-27允许未登录查看影片信息
if(empty($_GET['movie'])){
	header("Location:./");
	exit;
}

	  /*
	   * 页面初始化信息，包括标题，js和css
	   */
	  $movie_title = $_GET['movie'];

	  $page_title = $movie_title;

	  $js_files = array("init.js","movie_view.js","comment.js");

	  $css_files = array("ajax.css","movie_info.css");

	  //follow
    $ff = new Follow();
    //Movie
    $m = new Movie();

	  include_once 'assets/common/header.inc.php';

	  $i = $m->findMoive(1, $movie_title)[0];

 ?>

 <div class="container">
<br />
<br />
<br />
<h4><strong><?php echo $page_title ?></strong> </h4>
<div class="body">
 	<div class="post_img">
        <img src="../douban/img/<?php echo $i['title'].'.jpg' ?>" alt="<?php echo $i['title'] ?>"
			class="img-responsive img-thumb" >
	</div>
	<div class="movie_info">
		<p>导演：<?php echo $i['director']; ?></p>
        <p>类型：<?php echo $i['types']; ?></p>
        <p>主演：<?php echo $i['actor']; ?></p>
        <p>编剧：<?php echo $i['writer']; ?></p>
        <p>地区：<?php echo $i['country']; ?></p>
        <p>语言：<?php echo $i['lang']; ?></p>
        <p>上映时间：<?php echo $i['ontime']; ?></p>
        <p>片长：<?php echo $i['runtime']; ?></p>
        <p>又名：<?php echo $i['another']; ?></p>
        <p>评分：<?php echo sprintf("%.1f", $i['average']); ?></p>
        <p>共<?php echo $i['votes']; ?>人参与投票</p>
        <p><?php echo $i['summary']; ?></p>
       </div>
 </div>

<br/>
<label> 剧照： </label>
        <!--剧照显示(Carousel)-->
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
 		 <!-- Indicators -->
  			<ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <div>
      <img src="../douban/img/<?php echo $i['title'].'/0.jpg' ?>" alt="0"> 
      </div>
    </div>
    <div class="item">
     <div>
      <img src="../douban/img/<?php echo $i['title'].'/1.jpg' ?>" alt="1">
      </div>
    </div>
     <div class="item">
      <div>
      <img src="../douban/img/<?php echo $i['title'].'/2.jpg' ?>" alt="2">
      </div>
    </div>
     <div class="item">
      <div>
      <img src="../douban/img/<?php echo $i['title'].'/3.jpg' ?>" alt="3">
      </div>
    </div>
  </div>

  <!-- Controls -->
  	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    	<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    	<span class="sr-only">Previous</span>
  	</a>
  		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    	<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    	<span class="sr-only">Next</span>
  		</a>
	</div>
	<!--^^^剧照显示(Carousel)-->

<!-- 关注这部影片的人 -->
<div class="follow_this">
<br />
<label>他们也收藏这部影片</label>
  <div>
  <?php foreach ($ff->getFollowUser($i['id']) as $u): ?>
      <span data-toggle="tooltip" data-placement="top" title="<?php echo $u['user_name'] ?>"><a href="javascript:void(0);" onclick="is_login(<?php echo (isset($_SESSION['user']) ? 'true' : 'false') ?>,<?php echo $u['user_id']; ?>)"><span>
        <img src="<?php echo $u['user_img'] ?>" class="img-responsive img-thumbnail" style="width:70px;">
      </span></a></span>&nbsp;
  <?php endforeach ?>
  </div>
</div>

<!-- 未登陆则显示警告模态框 -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="warn_login">
  <div class="modal-dialog modal-sm">
      <div class="alert alert-danger" role="alert">
      <h5 align="center"><strong>警告：</strong></h5>
      <p align="center">你需要登录先！</p> </div>
  </div>
</div>
<!-- 输入为空则显示警告模态框 -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="warn_input">
  <div class="modal-dialog modal-sm">
      <div class="alert alert-danger" role="alert">
      <h5 align="center"><strong>警告：</strong></h5>
      <p align="center">未输入任何字符！</p> </div>
  </div>
</div>
<?php include_once '_comments.php'; ?>

</div>
<?php include_once 'assets/common/footer.inc.php'; ?>