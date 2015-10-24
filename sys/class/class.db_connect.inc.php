<?php 
/*
 * PHP 5.6
 * 数据库连接的封装类（2015-10-13）
 * @author dai@2015
 * 
 */

class DB_Connect{
	//数据库对象，子类可继承，外部不可引用
	protected $db;

	/*
	 * 构造函数，供子类继承，生成数据库对象
	 * @param object $dbo 数据库对象，若已有可用的数据库对象，则使用，否则
	 * 从配置中自己生成mysql连接
	 */
	protected function __construct($dbo=NULL){
		if(is_object($dbo))
		{
			//检查对象是否为可用数据库对象
			$this->db=$dbo;
		}
		else 
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			try
			{
				//从config中生成PDO连接对象
				$this->db = new PDO($dsn,DB_USER,DB_PASS);
				//解决中文乱码的问题
				$this->db->query('set names utf8;'); 
			}
			catch(Exception $e)
			{
				die($e->getMessage());
			}
		}
	}
}
?>