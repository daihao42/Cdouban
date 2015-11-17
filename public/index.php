<?php

//包含必要的配置信息
include_once '../sys/core/init.inc.php';

//如果是查找GET到本页面，获取查找的输入值
if(!empty($_GET['city']))
	  {
	  	$wea = new Weather($dbo, $_GET['city']);
	  }
	  else
	  {
	  	$wea = new Weather($dbo);
	  }

//如果是有分页的查询，则取出分页数据,否则默认为1
if(!empty($_GET['page']))
    {
      $page = $_GET['page'];
    }
  else{
    $page = 1;
  }


//test movie ok!!
	  $movie = new Movie();

    //follow
    $ff = new Follow();

                    //连接本地的 Redis 服务
        $redis = new Redisdb(0);

    //页面总数
    $pagecount = $movie->pagecount;

	  /*
	   * 页面初始化信息，包括标题，js和css
	   */
	  $page_title = "首页";

	  $js_files = array("init.js");

	  $css_files = array("ajax.css","movie.css");

    //未设置或设置筛选为空，则设置cookie中的type为all
    if(!isset($_SESSION['type']))
    {
      $_SESSION['type'] = array();
      $_SESSION['type'][0] = '全部';
      $_SESSION['ontimes'] = array();
      $_SESSION['ontimes'][0] = '全部';
      $_SESSION['country'] = array();
      $_SESSION['country'][0] = '全部';
      $arr = $movie->findMoive($page);  
    }
    else
    {
      //将所有筛选取并集
      $type = array_merge($_SESSION['type'],$_SESSION['ontimes'],$_SESSION['country']);
        $arr = array();
        foreach ($redis->getUnion($type) as $i)
        { 
          echo $i;
          $arr[] = $movie->findMoiveByID($i)[0];
          //echo is_array($dsad);
        }
    }


	  include_once 'assets/common/header.inc.php';
?>

<div class="container">
<br />
<br />
<!--影片推荐 -->
<?php $hotarr = $movie->getHotMovie() ?>
<h2>热门推荐：</h2>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
    <div>
      <img src="../douban/img/<?php echo $hotarr[0]['title'].'.jpg' ?>" 
      	class="img-responsive img-thumbnail" alt="...">
      </div>
    </div>
    <div class="item">
    <div>
      <img src="../douban/img/<?php echo $hotarr[1]['title'].'.jpg' ?>" 
      	class="img-responsive img-thumbnail" alt="...">
      </div>
    </div>
	<div class="item">
    <div>
      <img src="../douban/img/<?php echo $hotarr[2]['title'].'.jpg' ?>" 
      	class="img-responsive img-thumbnail" alt="...">
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
<!--^^^^影片推荐 -->

<hr />

<div class="type-filter">
<!-- 选择类型筛选 -->
<p>
类型：
<?php $types = $redis->getType('type'); ?>
<!-- 单独为全部留下的链接 -->
    <?php $choose = in_array('全部',$_SESSION['type'],True); ?>
    <?php if(!empty($choose)): ?>
<a href="javascript:void(0);" style="color:red;" onclick="changeType(this,'type')">全部</a>
    <?php else: ?>
 <a href="javascript:void(0);" onclick="changeType(this,'type')">全部</a>     
    <?php endif; ?>
<!-- ^^^单独为全部留下的链接 -->
<?php foreach($types as $i): ?>
    <?php $choose = in_array($i,$_SESSION['type'],True); ?>
    <?php if(!empty($choose)): ?>
<a href="javascript:void(0);" style="color:red;" onclick="changeType(this,'type')"><?php echo $i ?></a>
    <?php else: ?>
 <a href="javascript:void(0);" onclick="changeType(this,'type')"><?php echo $i ?></a>     
    <?php endif; ?>
<?php endforeach; ?>
</p>
<!-- ^^^^选择类型筛选 -->

<!-- 选择上映时间筛选 -->
<p>
上映时间：
<?php $ontime = $redis->getType('ontimes'); ?>
<!-- 单独为全部留下的链接 -->
    <?php $choose = in_array('全部',$_SESSION['ontimes'],True); ?>
    <?php if(!empty($choose)): ?>
<a href="javascript:void(0);" style="color:red;" onclick="changeType(this,'ontimes')">全部</a>
    <?php else: ?>
 <a href="javascript:void(0);" onclick="changeType(this,'ontimes')">全部</a>     
    <?php endif; ?>
<!-- ^^^单独为全部留下的链接 -->
<?php foreach($ontime as $i): ?>
    <?php $choose = in_array($i,$_SESSION['ontimes'],True); ?>
    <?php if(!empty($choose)): ?>
<a href="javascript:void(0);" style="color:red;" onclick="changeType(this,'ontimes')"><?php echo $i ?></a>
    <?php else: ?>
 <a href="javascript:void(0);" onclick="changeType(this,'ontimes')"><?php echo $i ?></a>     
    <?php endif; ?>
<?php endforeach; ?>
</p>
<!-- ^^^选择上映时间筛选 -->

<!-- 地区筛选 -->
<p>
地区：
<?php $country = $redis->getType('country'); ?>
<!-- 单独为全部留下的链接 -->
    <?php $choose = in_array('全部',$_SESSION['country'],True); ?>
    <?php if(!empty($choose)): ?>
<a href="javascript:void(0);" style="color:red;" onclick="changeType(this,'country')">全部</a>
    <?php else: ?>
 <a href="javascript:void(0);" onclick="changeType(this,'country')">全部</a>     
    <?php endif; ?>
<!-- ^^^单独为全部留下的链接 -->
<?php foreach($country as $i): ?>
    <?php $choose = in_array($i,$_SESSION['country'],True); ?>
    <?php if(!empty($choose)): ?>
<a href="javascript:void(0);" style="color:red;" onclick="changeType(this,'country')"><?php echo $i ?></a>
    <?php else: ?>
 <a href="javascript:void(0);" onclick="changeType(this,'country')"><?php echo $i ?></a>     
    <?php endif; ?>
<?php endforeach; ?>
</p>
<!-- ^^^上映地区筛选 -->
</div>

<!--影片展示-->
<div class="row">
<?php 
      $cc = new Comment();
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
                <!--关注该影片的人数 -->
        <i class="glyphicon glyphicon-star" ><?php echo $ff->getFollowNum($i['id']) ?></i>
        <!-- 评论数-->
        <i class="glyphicon glyphicon-comment" style="float:right;"><?php echo $cc->countComment($i['id']); ?></i>
        </p>
        <p>
        <!-- 判断是否登陆，再判断是否关注，然后为i赋予不同颜色 -->
        <?php if(isset($_SESSION['user']) && $ff->isFollow($_SESSION['user']['id'],$i['id'])): ?>
          <i class="fa fa-star fa-2x" style="color:rgb(219, 112, 147);" onclick="follow(<?php if(!isset($_SESSION['user'])){echo 0;} else{echo $_SESSION['user']['id'];} ?>,<?php echo $i['id'] ?>,this);"></i>
          <?php else: ?>
          <i class="fa fa-star fa-2x" style="color:black;" onclick="follow(<?php if(!isset($_SESSION['user'])){echo 0;} else{echo $_SESSION['user']['id'];} ?>,<?php echo $i['id'] ?>,this);"></i>
          <?php endif ?>
        <!-- ^^^^判断是否关注，然后为i赋予不同颜色 -->
        <!-- 更多链接 -->
        <a href="./movie_view.php?movie=<?php echo $i['title'] ?>"><span class="label label-success" style="float:right;">更多
                </span></a>
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

<!-- 未登陆则显示警告模态框 -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="warn_login">
  <div class="modal-dialog modal-sm">
      <div class="alert alert-danger" role="alert">
      <h5 align="center"><strong>提示：</strong></h5>
      <p align="center">收藏功能需要登录！</p> </div>
  </div>
</div>
<!-- ^^^未登陆则显示警告模态框 -->

<!--分页显示   (准备采用ajax)-->
<nav align="center">
  <ul class="pagination pagination-lg pager">

  <?php if($page >= $pagecount): ?>
    <li class="previous"><a href=".?page=<?php echo $page - 1 ?>"><span aria-hidden="true">&larr;</span> Pre</a></li>
    <li class="next disabled"><a href="#">Nex <span aria-hidden="true">&rarr;</span></a></li>
  <?php elseif($page == 1): ?>
    <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Pre</a></li>
    <li class="next"><a href=".?page=<?php echo $page + 1 ?>">Nex <span aria-hidden="true">&rarr;</span></a></li>
  <?php else: ?>
    <li class="previous"><a href=".?page=<?php echo $page - 1 ?>"><span aria-hidden="true">&larr;</span> Pre</a></li>
    <li class="next"><a href=".?page=<?php echo $page + 1 ?>">Nex <span aria-hidden="true">&rarr;</span></a></li>
  <?php endif ?>

    <?php for ($i=1; $i<=$pagecount ; $i++): ?>
      <?php if($i == $page): ?>
      <li class="active"><a href="#"><?php echo $i ?></a></li>
    <?php else: ?>
        <li><a href=".?page=<?php echo $i ?>"><?php echo $i ?></a></li>
      <?php endif ?>
      <?php endfor ?>

  </ul>
</nav>
<!--^^^^分页显示-->
<!--
<?php if(isset($_SESSION['user'])): ?>
  -->
<!-- 显示user城市-->
<!-- 取消于2015-10-28,待新功能上线
<?php 
    $city = $wea->getUserCity($_SESSION['user']['name']);
    $swea = new Weather($dbo, $city[0][0]);
    $arr = $swea->buildCityInfo();
 ?>
 <div id="content" class="content">
  <h6><?php echo $arr[0]->city; ?>:</h6>
  <ul>
    <li>温度：<?php echo $arr[0]->temp; ?></li>
    <li>风向：<?php echo $arr[0]->windfrom; ?></li>
    <li>风级：<?php echo $arr[0]->winddegree; ?></li>
    <li>湿度：<?php echo $arr[0]->dampness; ?></li>
    <li>现场情况：<?php echo $arr[0]->njd; ?></li>
    <li>气压：<?php echo $arr[0]->qy; ?></li>
    <li>更新时间：<?php echo $arr[0]->updatetime; ?></li>
  </ul>
</div>
<?php endif ?>

<form action="." method="get">
	<input type="text" name="city" autocomplete="off" placeholder="城市"/>
	<input type="submit" value="查询天气"/>
</form>
 -->

<!--
<?php $arr = $wea->buildCityInfo();
	  foreach ($arr as $inf):
?>
	<h6><a target="_blank" href="./view.php?city=<?php echo $inf->city; ?>"><?php echo $inf->city; ?>:</a></h6>
	<?php endforeach; ?>
-->
<!--
<?php if(!empty($_GET['city'])){
  $inf = $wea->buildCityInfo()[0];
 ?>
 <h6><a target="_blank" href="./view.php?city=<?php echo $inf->city; ?>"><?php echo $inf->city; ?>:</a></h6>
 <?php } ?>
 -->
</div>
<?php include_once 'assets/common/footer.inc.php'; ?>