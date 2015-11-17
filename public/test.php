<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
<?php
	include_once '../sys/core/init.inc.php';
	//follow
    //echo phpinfo();
    //连接本地的 Redis 服务
   $movie = new Movie();
   $arr = array();
   for($i=1;$i<3;$i++){
    $arr[] = $movie->findMoiveByID(1)[0];
   }
   print_r($arr);

?>
</html>