<?php 

/**
 * 处理用户关注电影行为(2015-10-18)
 * PHP 5.6
 * @author dai@2015
 *
 */

class Follow extends DB_Connect
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
	 * @param int userID 关注用户的ID
	 * @param int movieID 被关注影片的ID
	 */
	public function upFollow($userID,$movieID)
	{	
		//插入数据进follow表中
 		$sql = "insert into `follow` (`user_id`,`movie_id`)
 				values (:uid, :mid)";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':uid',$userID,PDO::PARAM_INT);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
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
	 * @param int userID 关注用户的ID
	 * @param int movieID 被关注影片的ID
	 */
	public function downFollow($userID,$movieID)
	{
		//删除follow表中数据
 		$sql = "delete from `follow` where `user_id`=:uid and `movie_id`=:mid";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':uid',$userID,PDO::PARAM_INT);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
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
	 * 查询影片关注人数
	 * @param int movieID 被关注影片的ID
	 * @return int 关注该影片总人数
	 */
	public function getFollowNum($movieID)
	{
		$sql = "select * from `follow` where `movie_id`=:mid";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
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
 			return 0;
 		}
 		else
 		{
 			return count($follow);
 		}
	}

	/**
	 * 查询用户关注的所有电影
	 * @param int userID 关注用户的ID
	 * @return array 用户关注的所有电影
	 */
	public function getUserFollow($userID)
	{
		$sql = "select `movie_id` from `follow` where `user_id`=:uid";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':uid',$userID,PDO::PARAM_INT);
 			$stmt->execute();
 			$follow = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		//返回的资源数组
 		$res = array();

 		foreach ($follow as $i) {
 			//查询单部电影 where + limit
			$sql = "select `id`,`title`,`director`,`writer`,`actor`,`runtime`,`another`,
					`types`,`country`,`lang`,`ontime`,`summary`,`average`,`votes` from `movie_info` where `id`=:id limit 1";
			try
			{
				$stmt = $this->db->prepare($sql);
				/*
				 * 如果$id有效，则绑定到sql语句中，
				 * 该参数在execute()函数中生效，替换掉:id占位的参数
				 */
				$stmt->bindParam(":id",$i['movie_id'],PDO::PARAM_INT);
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
	 * 查询用户是否关注了该影片
	 * @return bool 是否
	 */
	public function isFollow($userID,$movieID)
	{
		$sql = "select * from `follow` where `user_id`=:uid and `movie_id`=:mid limit 1";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':uid',$userID,PDO::PARAM_INT);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
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
	 * 查询影片关注的人具体是哪些
	 * @param int movieID
	 * @return array 用户数据
	 */
	public function getFollowUser($movieID)
	{
		$sql = "select `user_id` from `follow` where `movie_id`=:mid";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
 			$stmt->execute();
 			$follow = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		//返回的资源数组
 		$res = array();
 		foreach ($follow as $i) {
 			//查询单部电影 where + limit
			$sql = "select `user_id`,`user_name`,`user_email`,`user_city` ,`user_img` from `users` where `user_id`=:id limit 1";
			try
			{
				$stmt = $this->db->prepare($sql);
				/*
				 * 如果$id有效，则绑定到sql语句中，
				 * 该参数在execute()函数中生效，替换掉:id占位的参数
				 */
				$stmt->bindParam(":id",$i['user_id'],PDO::PARAM_INT);
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

}

 ?>