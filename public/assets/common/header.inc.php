<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 页面标题在页内赋值 -->
    <title><?php echo $page_title ?></title>
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->

    <!--IE兼容性-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
  <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

    <!-- jQuery -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/footer.css" />
    <!--在页内通过数组来包含需要的js文件 -->
    <?php foreach($js_files as $js): ?>
    	<script src="assets/js/<?php echo $js; ?>"></script>
    <?php endforeach; ?>
    <!--在页内通过数组来包含需要的css文件 -->
    <?php foreach($css_files as $css): ?>
    	<link rel="stylesheet" type="text/css" href="assets/css/<?php echo $css; ?>">
    <?php endforeach; ?>
    </head>
<body>

  <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-example-js-navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./">BigC</a>
        </div>
        <div class="collapse navbar-collapse bs-example-js-navbar-collapse">

                <?php if(isset($_SESSION['user'])): ?>
            <!-- 用户已登录 -->

          <ul class="nav navbar-nav navbar-right">
            <li id="fat-menu" class="dropdown">
              <a id="drop3" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-user"></i>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="drop3">
                <li><a href="./user_view.php?id=<?php echo $_SESSION['user']['id']; ?>">个人关注</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li>
                  <form class="navbar-form navbar-right" action="assets/inc/process.inc.php" method="post">
                  <div>
                  <input type="submit" value="退出" class="btn btn-danger btn-lg" />
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                  <input type="hidden" name="action" value="user_logout">
                  </div>
                  </form>
                </li>
              </ul>
            </li>
          </ul>

                    <?php else: ?>
            <!-- 若用户未登录 -->
          <form class="navbar-form navbar-right" action="assets/inc/process.inc.php" method="post">
            <div class="form-group">
              <input type="text" name="uemail" id="uemail" placeholder="用户邮箱" 
                     required class="form-control">
            </div>
            <div class="form-group">
              <input type="password" name="pword" id="pword" placeholder="密码" 
                     required class="form-control">
            </div>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
            <input type="hidden" name="action" value="user_login">
            <button type="submit" class="btn btn-success">登录</button>
            <button type="button" class="btn btn-info"  onclick="location.href='./register.php'">注册</button>
          </form>
          <?php endif ?>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container-fluid -->
    </nav> <!-- /navbar-example -->
