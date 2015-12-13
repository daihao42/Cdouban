<?php
	/*
	 * PHP 5.6
	 * ）
	 * @author dai@2015
	 * 
	 */

	/*
	 * 启用session
	 */
	session_start();

	/*
	 * 如果session没有防跨站请求标记则生成一个
	 */
	if(!isset($_SESSION['token']))
	{
		$_SESSION['token'] = sha1(uniqid(mt_rand(),TRUE));
	}

	//包含必要的配置信息
	include_once '../sys/config/db-cred.inc.php';
	//foreach读出配置常量并定义
	foreach($C as $name => $val){
		define($name, $val);
	}

	//PDO对象生成
	$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
	$dbo = new PDO($dsn,DB_USER,DB_PASS);
	//解决中文乱码的问题
	$dbo->query('set names utf8;'); 

	//定义自动载入类的__autoload函数
	function __autoload($class)
	{
		$filename = "../sys/class/class.".$class.".inc.php";
		if(file_exists($filename))
		{
			include_once $filename;
		}
	}
?>