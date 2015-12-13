<?php 

/**
 * 处理用户关注用户行为(2015-10-27)
 * PHP 5.6
 * @author dai@2015
 *
 */

class Attention extends DB_Connect
{
	/**
	 * 构造函数
	 * @param object $dbo 数据库对象，默认为mysql
	 */
	public function __construct($dbo=NULL)
	{
		//调用父类构造函数，生成数据库对象
		parent::__construct($dbo);
	}

	/**
	 * 添加一条关注信息
	 * @param int attenID 关注用户的ID
	 * @param int attenedID 被关注用户的ID
	 */
	public function upAttention($attenID,$attenedID)
	{
		//插入一条记录
		$sql = "insert into `attention` (`atten_id`,`attened_id`)
 				values (:nid, :edid)";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':nid',$attenID,PDO::PARAM_INT);
 			$stmt->bindParam(':edid',$attenedID,PDO::PARAM_INT);
 			$stmt->execute();
 			$stmt->closeCursor();
 			return TRUE;
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
	}

	/**
	 * 取消一条关注信息
	 * @param int attenID 关注用户的ID
	 * @param int attenedID 被关注用户的ID
	 */
	public function downAttention($attenID,$attenedID)
	{
		//删除表中数据
 		$sql = "delete from `attention` where `atten_id`=:nid and `attened_id`=:edid";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':nid',$attenID,PDO::PARAM_INT);
 			$stmt->bindParam(':edid',$attenedID,PDO::PARAM_INT);
 			$stmt->execute();
 			$stmt->closeCursor();
 			return TRUE;
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
	}

	/**
	 * 查询用户是否关注了该用户
	 * @return bool 是否
	 */
	public function isAttention($attenID,$attenedID)
	{
		$sql = "select * from `attention` where `atten_id`=:nid and `attened_id`=:edid limit 1";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':nid',$attenID,PDO::PARAM_INT);
 			$stmt->bindParam(':edid',$attenedID,PDO::PARAM_INT);
 			$stmt->execute();
 			$follow = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		if(empty($follow))
 		{
 			return FALSE;
 		}
 		else
 		{
 			return TRUE;
 		}
	}

	/**
	 * 查询关注我的人(粉丝)具体是哪些,仅允许用户自己查看
	 * @param int attenedID 被关注用户的ID
	 * @return array 用户数据
	 */
	public function getAttentionUser($attenedID)
	{
		$sql = "select `atten_id` from `attention` where `attened_id`=:edid";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':edid',$attenedID,PDO::PARAM_INT);
 			$stmt->execute();
 			$atten = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		//返回的资源数组
 		$res = array();
 		foreach ($atten as $i) {
 			//用户查询
			$sql = "select `user_id`,`user_name`,`user_img` from `users` where `user_id`=:id limit 1";
			try
			{
				$stmt = $this->db->prepare($sql);
				/*
				 * 如果$id有效，则绑定到sql语句中，
				 * 该参数在execute()函数中生效，替换掉:id占位的参数
				 */
				$stmt->bindParam(":id",$i['atten_id'],PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				$res[] = $results[0];
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
 		}
 		return $res;
	}


	/**
	 * 他的关注，允许未登录用户查看
	 * @param attenID int 关注人的ID
	 * @return array 该关注人关注的所有用户
	 */
	public function getAttenedUser($attenID)
	{
		$sql = "select `attened_id` from `attention` where `atten_id`=:nid";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':nid',$attenID,PDO::PARAM_INT);
 			$stmt->execute();
 			$atten = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		//返回的资源数组
 		$res = array();
 		foreach ($atten as $i) {
 			//用户查询
			$sql = "select `user_id`,`user_name`,`user_img` from `users` where `user_id`=:id limit 1";
			try
			{
				$stmt = $this->db->prepare($sql);
				/*
				 * 如果$id有效，则绑定到sql语句中，
				 * 该参数在execute()函数中生效，替换掉:id占位的参数
				 */
				$stmt->bindParam(":id",$i['attened_id'],PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				$res[] = $results[0];
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
 		}
 		return $res;
	}

	/**
	 * 统计关注数目
	 * @param string 统计类型，关注数或被关注数
	 * @param int ID 需要统计的对象ID
	 * @return int 统计结果
	 */
	public function countAttenNum($mode,$id)
	{	
		//替换表名不能像参数一样绑定，只能使用字符串替换
		$sql = str_replace("mode",$mode,"select `id` from `attention` where `mode`=:nid");
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':nid',$id,PDO::PARAM_INT);
 			$stmt->execute();
 			$atten = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		return count($atten);
	}
}
 ?>
