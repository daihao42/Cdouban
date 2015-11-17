<?php 
/**
 * 管理（添加，修改等）评论行为
 * PHP 5.6
 * @author dai@2015
 * reply 模块，to_comment字段表明是对那一条评论
 * to_who字段表明是对谁回复，可为0
 */
class Reply extends DB_Connect
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
	 * 记录回复
	 * @param _POST提交的数据
	 * @post int author_id 回复用户的ID
	 * @post int to_who 回复回复的用户的ID，可以为0
	 * @post int to_comment 回复的评论的ID，不可为0
	 * @post string content 评论的内容
	 * @return bool 插入成功
	 */
	public function addReply()
	{
		$sql = "insert into `reply` (`author_id`,`to_who`,`to_comment`,`content`)
 				values (:aid, :to_w, :to_c, :cont)";
 			//如果设置了to_w，即是回复的回复，则插入被回用户ID，否则为0
 		if(isset($_POST['to_who']))
 		{
 			$to_w = $_POST['to_who'];
 		}
 		else 
 		{
 			$to_w = 0;
 		}
 		$aid = $_POST['author_id'];
 		$to_c = $_POST['to_comment'];
 		$cont = $_POST['content'];
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':aid',$aid,PDO::PARAM_INT);
 			$stmt->bindParam(':to_w',$to_w,PDO::PARAM_INT);
 			$stmt->bindParam(':to_c',$to_c,PDO::PARAM_INT);
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
 	 * 统计评论有多少回复
 	 * @param int commID 评论ID
 	 * @return int count 回复数
 	 */
 	public function countComment($commID)
 	{
 		$sql = "select * from `reply` where `to_comment`=:to_c";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':to_c',$commID,PDO::PARAM_INT);
 			$stmt->execute();
 			$rep = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		return count($rep);
 	}
 	
 	/**
 	 * 取回回复内容
 	 * @param int commentID 评论的ID
 	 * @return array 回复的内容
 	 */
 	public function getReplyByComment($commentID)
 	{
 		$sql = "select * from `reply` where `to_comment`=:to_c order by `time` desc";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':to_c',$commentID,PDO::PARAM_INT);
 			$stmt->execute();
 			$rep = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		return $rep;
 	}

}

 ?>