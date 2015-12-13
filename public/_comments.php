<hr />
<label class="control-label">评论：</label>
<?php //登陆才显示评论框
	  if(isset($_SESSION['user'])):  
 ?>
 <div class="comment_forms">
 <form class="form-vertical" action="assets/inc/process.inc.php" method="post">
        <div class="form-group">
          <div class="controls">
          <textarea name="content" wrap="soft" class="form-control" required style="resize:none;" placeholder="说点什么" rows=4></textarea>
            <div class="help-block"></div>
          </div>
        </div>

        <div class="form-group">
        	<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			<input type="hidden" name="action" value="comment_post">
			<input type="hidden" name="author_id" value="<?php echo $_SESSION['user']['id'] ?>">
			<input type="hidden" name="movie_id" value="<?php echo $i['id'] ?>">
			<input type="submit" class="btn btn-success" onclick="return n2br();" value="评论"></input>
          </div>
	</form>
 </div>
<?php endif ?>

<!-- 评论显示 -->
<?php $cc = new Comment();
      $rr = new Reply();
 	  $ccs = $cc->getMovieComment($i['id']);
 	  foreach ($ccs as $i):
 	?>
<div class="alert alert-info" role="alert">
 <div class="media">
  <div class="media-left media-middle">
    <a href="javascript:void(0);" onclick="is_login(<?php echo (isset($_SESSION['user']) ? 'true' : 'false') ?>,<?php echo $i['user_id']; ?>)">
      <img class="media-object img-responsive img-thumbnail img-circle" style="width:60px;" src="<?php echo $i['user_img'] ?>" alt="...">
    </a>
  </div>
  <div class="media-body">
    <h4 class="media-heading">
    <a href="javascript:void(0);" onclick="is_login(<?php echo (isset($_SESSION['user']) ? 'true' : 'false') ?>,<?php echo $i['user_id']; ?>)">
    <?php echo $i['user_name']; ?></a><span style="color:gray;float:right;font-size:2px;">发表于：<?php echo $i['time']; ?></span></h4>
    <p><?php echo $i['content']; ?>
    <!-- 在评论中插入回复的pre和i -->
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="list_reply" id="<?php echo $i['id'] ?>rep" href="javascript:void(0);" onclick="addReply(<?php echo $i['id'] ?>)">回复<i class="glyphicon glyphicon-chevron-down"></i></a>
        (<?php echo $rr->countComment($i['id']) ?>)

	<!-- 在评论中插入回复的pre和i -->
    </p> 
  </div>
 </div>
</div>
<?php endforeach; ?>
<!-- ^^^评论显示 -->

<!-- 嵌入式显示回复块，利用ajax取出 -->
<!-- 利用js已实现 -->
<!-- ^^^嵌入式显示回复块，利用ajax取出 -->