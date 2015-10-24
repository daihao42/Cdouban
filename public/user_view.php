<?php 

//包含必要的配置信息
include_once '../sys/core/init.inc.php';
if(!isset($_SESSION['user'])){
	header("Location:./login.php");
	exit;
}

	  /*
	   * 页面初始化信息，包括标题，js和css
	   */
	  $page_title = $_SESSION['user']['name']."的关注";

	  $js_files = array("init.js");

	  $css_files = array("ajax.css","movie.css");

	  //follow
    $ff = new Follow();

	  include_once 'assets/common/header.inc.php';
?>

<br />
<br />
<br />
<!--影片展示-->
<div class="row">
<?php $arr = $ff->getUserFollow($_SESSION['user']['id']);
	  $id = 0;
	  foreach ($arr as $i):
?>
  <div class="col-sm-6 col-md-2 col-xs-6">
    <div class="thumbnail">
		<a data-toggle="modal" data-target="#<?php echo $id ?>myModal">
		<img id="movie-post" src="../douban/img/<?php echo $i['title'].'.jpg' ?>" alt="<?php echo $i['title'] ?>"
			 class="img-thumbnail"></a>
			<h5><?php echo $i['title'] ?></h5>
        <p>导演：<?php echo $i['director'] ?></p>
        <p>类型：<?php echo $i['types'] ?></p>
        <p>
        <!-- 判断是否登陆，再判断是否关注，然后为i赋予不同颜色 -->
        <?php if(isset($_SESSION['user']) && $ff->isFollow($_SESSION['user']['id'],$i['id'])): ?>
          <i class="fa fa-star fa-2x" style="color:rgb(219, 112, 147);" onclick="follow(<?php if(!isset($_SESSION['user'])){echo 0;} else{echo $_SESSION['user']['id'];} ?>,<?php echo $i['id'] ?>,this);"></i>
          <?php else: ?>
          <i class="fa fa-star fa-2x" style="color:black;" onclick="follow(<?php if(!isset($_SESSION['user'])){echo 0;} else{echo $_SESSION['user']['id'];} ?>,<?php echo $i['id'] ?>,this);"></i>
          <?php endif ?>
        <!-- ^^^^判断是否关注，然后为i赋予不同颜色 -->

        <!--关注该影片的人数 -->
        <button class="btn btn-primary" type="button" style="float:right;"><span class="badge"><?php echo $ff->getFollowNum($i['id']) ?></span>
                </button>
        </p>
      </div>
    </div>
    <!-- 模态框 -->
<div class="modal fade" id="<?php echo $id ?>myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $i['title'] ?></h4>
      </div>
      <div class="modal-body">
        <img src="../douban/img/<?php echo $i['title'].'.jpg' ?>" alt="<?php echo $i['title'] ?>"
			class="img-responsive img-thumb" >
			<hr />
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
        <!--剧照显示(Carousel)-->
        <div id="<?php echo $id ?>carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#<?php echo $id ?>carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#<?php echo $id ?>carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#<?php echo $id ?>carousel-example-generic" data-slide-to="2"></li>
    <li data-target="#<?php echo $id ?>carousel-example-generic" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="../douban/img/<?php echo $i['title'].'/0.jpg' ?>" alt="0"> 
    </div>
    <div class="item">
      <img src="../douban/img/<?php echo $i['title'].'/1.jpg' ?>" alt="1">
    </div>
     <div class="item">
      <img src="../douban/img/<?php echo $i['title'].'/2.jpg' ?>" alt="2">
    </div>
     <div class="item">
      <img src="../douban/img/<?php echo $i['title'].'/3.jpg' ?>" alt="3">
    </div>

  </div>

  <!-- Controls -->
  	<a class="left carousel-control" href="#<?php echo $id ?>carousel-example-generic" role="button" data-slide="prev">
    	<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    	<span class="sr-only">Previous</span>
  	</a>
  		<a class="right carousel-control" href="#<?php echo $id ?>carousel-example-generic" role="button" data-slide="next">
    	<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    	<span class="sr-only">Next</span>
  		</a>
	</div>
	<!--^^^剧照显示(Carousel)-->
      </div>
    </div>
  </div>
</div>
<!-- ^^^模态框 -->

<?php $id++;
	  endforeach; ?>
</div>

<!--^^^影片展示-->
<?php include_once 'assets/common/footer.inc.php'; ?>