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
    $ff = new Follow();
    print_r($ff->getUserFollow($_SESSION['user']['id']))
?>
</html>