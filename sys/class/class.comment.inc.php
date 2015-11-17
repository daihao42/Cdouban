<?php 
/**
 * 管理（添加，修改等）评论行为
 * PHP 5.6
 * @author dai@2015
 *
 */
class Comment  extends DB_Connect
{
	 /*
 	 * 保存生成一个DB对象，设定盐的长度
 	 * @param object $db 数据库对象
 	 */
 	function __construct($db=NULL)
 	{
 		parent::__construct($db);
 	}

	/**
	 * 记录评论
	 * @param _POST提交的数据
	 * @post int author_id 评论用户的ID
	 * @post int movie_id 评论的电影ID
	 * @post int to_comment 回复的评论的ID，默认为0
	 * @post string content 评论的内容
	 * @return bool 插入成功
	 */
	public function addComment()
	{
		$sql = "insert into `comment` (`author_id`,`movie_id`,`content`)
 				values (:aid, :mid, :cont)";
 		$aid = $_POST['author_id'];
 		$mid = $_POST['movie_id'];
 		$cont = $_POST['content'];
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':aid',$aid,PDO::PARAM_INT);
 			$stmt->bindParam(':mid',$mid,PDO::PARAM_INT);
 			$stmt->bindParam(':cont',$cont,PDO::PARAM_STR);
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
 	 * 通过影片ID查看评论及其用户
 	 * @param int movie_id 影片ID
 	 * @return array 影评及用户信息
 	 */
 	public function getMovieComment($movieID)
 	{
 		$sql = "select `id`,`author_id`,`time`,`content` from `comment` where `movie_id`=:mid order by `time` desc";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
 			$stmt->execute();
 			$comm = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		//获取发布评论人的对应信息
 		$res = array();
 		$sql = "select `user_id`,`user_name`,`user_email`,`user_city` ,`user_img` from `users` where `user_id`=:id limit 1";
 		foreach ($comm as $i) {
 					try
 					{
 						$stmt = $this->db->prepare($sql);
 						$stmt->bindParam(":id",$i['author_id'],PDO::PARAM_INT);
 						$stmt->execute();
 						$user = $stmt->fetchAll();
 						$stmt->closeCursor();
 					}
 					catch(Exception $e)
 					{
 						die($e->getMessage());
 					}
 					//合并数组，将两个数组去重合并在一起
 			$res[] = array_merge($i,$user[0]);
 		}
 		return $res;
 	}

 	/**
 	 * 统计影片有多少评论
 	 * @param int movieID 影片ID
 	 * @return int count 评论数
 	 */
 	public function countComment($movieID)
 	{
 		$sql = "select * from `comment` where `movie_id`=:mid";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':mid',$movieID,PDO::PARAM_INT);
 			$stmt->execute();
 			$comm = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		return count($comm);
 	}


}

 ?>