<?php 
	/*
 * 包含初始化文件
 */
	include_once '../../../sys/config/db-cred.inc.php';
	//foreach读出配置常量并定义
	foreach($C as $name => $val){
		define($name, $val);
	}

	/**
	 * 处理上传图片（文件）并返回图片ID以供用户使用
	 */
	if (!empty($_FILES)) {
			$tmpname = $_FILES['file']['tmp_name'];
			//上传的文件名为$_FILES['file']['name'],获取后缀名
			$suffix = substr(strrchr($_FILES['file']['name'], '.'), 1);
			//利用时间生成唯一文件名
			$filename = md5(time() . mt_rand(1,1000000));
			$upFilePath = dirname(dirname(dirname(dirname(__FILE__)))).'\static\headimg\\'.$filename.'.'.$suffix;
			$ok=@move_uploaded_file($tmpname,$upFilePath);
			if($ok){
				echo $filename.'.'.$suffix;
			}
			else{
				echo 'FALSE';
			}
		}


 ?>