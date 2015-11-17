<?php 
/**
 * 推荐首页算法
 * copyright@2015 by dai
 * PHP 5.6
 */

class Recommend extends DB_Connect
{
	/**
	 * 设计推荐算法
	 * 目前将根据用户关注的人和收藏的电影推送(2015-11-3)
	 * 推送包括：
	 * 关注人的评论及收藏，收藏电影的新评论
	 */

	private $userID; //当前用户
	private $datesize=3; //每次显示的动态的时间间隔(目前10天)
	private $time; //数据库查询时间基准
	private $deadtime = '2015-10-29 00:00:00';

	/**
 	 * 保存生成一个DB对象，设定盐的长度
 	 * @param object $db 数据库对象
 	 * @param int date_sig 日期间隔，默认0,即时间限制为0*datesize+date(now)
 	 * @param int userID 当前用户的ID
 	 */
 	function __construct($userID=NULL,$date_sig=0,$db=NULL)
 	{
 		$this->userID = $userID;
 		parent::__construct($db);
 		$this->time = time() - $this->datesize*24*60*60*$date_sig;
 	}

 	/**
 	 * 检查时间是否超过最久的时间
 	 * @return bool 
 	 */
 	public function overTimeFlow()
 	{
 		if(strtotime(date('Y-m-d H:i:s',$this->time))>strtotime($this->deadtime))
 		{
 			return True;
 		}
 		else
 		{
 			return False;
 		}
 	}


 	public function test()
 	{
 		//return date('Y-m-d H:i:s',$this->time);
 		return $this->_getAttenFollow($this->userID);
 	}

 	/**
 	 * 获取时间间隔
 	 * 经过计算得出时间间隔
 	 * @return 时间
 	 */
 	private function _getTimestamp()
 	{
 		return $this->time - ($this->datesize*24*60*60);
 	}

 	/**
 	 * 获取当前用户关注的人
 	 * @param int userID 当前用户的ID
 	 * @return array 返回当前用户关注的ID数组
 	 */
 	private function _getAttention($userID)
 	{
 		$sql = "select `attened_id` from `attention` where `atten_id`=:nid";
		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':nid',$userID,PDO::PARAM_INT);
 			$stmt->execute();
 			$atten = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		return $atten;
 	}

 	/**
 	 * 获取当前用户收藏的电影
 	 * @param int userID 当前用户的ID
 	 * @return array 返回当前用户收藏的电影
 	 */
 	private function _getFollow($userID)
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
 		return $follow;
 	}

 	/**
 	 * 返回当前用户关注人所发表的评论
 	 * @param int userID 当前用户的ID
 	 * 首先调用_getAttention()获取关注列表
 	 * @return array 关注人发表评论的内容及对象
 	 */
 	private function _getAttenComment($userID)
 	{
 		//获取时间，转化为date形式
 		$date1 = date('Y-m-d H:i:s',$this->time);
 		$date2 = date('Y-m-d H:i:s',$this->_getTimestamp());

 	if($userID != NULL){
 		$aa = $this->_getAttention($userID);
 		$res = array();
 		foreach ($aa as $i) {
 			//注意between查询是从以前的时间到现在的时间
 			$sql = "select `id`,`author_id`,`time`,`content`,`movie_id` from `comment` where `author_id`=:uid 
 					and time between :d2 and :d1";
 			try
 			{
 				$stmt = $this->db->prepare($sql);
 				$stmt->bindParam(':uid',$i['attened_id'],PDO::PARAM_INT);
 				$stmt->bindParam(':d1',$date1,PDO::PARAM_STR);
 				$stmt->bindParam(':d2',$date2,PDO::PARAM_STR);
 				$stmt->execute();
 				$comm = $stmt->fetchAll();
 				$stmt->closeCursor();
 			}
 			catch(Exception $e)
 			{
 				die($e->getMessage());
 			}
 			//如果没有新评论，pass
 			if(!empty($comm))
 			{
 				//注意返回的是多重数组，并未limit 1
 				//解开多重数组
 				foreach ($comm as $k ) {
 					//添加类别码供前端识别
 					$k["type"] = 'ac';
 					$res[] = $k;
 				}
 			}
 		}
 	}
 		else {
 			//注意between查询是从以前的时间到现在的时间
 			$sql = "select `id`,`author_id`,`time`,`content`,`movie_id` from `comment` where 
 					time between :d2 and :d1";
 			try
 			{
 				$stmt = $this->db->prepare($sql);
 				$stmt->bindParam(':d1',$date1,PDO::PARAM_STR);
 				$stmt->bindParam(':d2',$date2,PDO::PARAM_STR);
 				$stmt->execute();
 				$comm = $stmt->fetchAll();
 				$stmt->closeCursor();
 			}
 			catch(Exception $e)
 			{
 				die($e->getMessage());
 			}
 			//如果没有新评论，pass
 			if(!empty($comm))
 			{
 				//注意返回的是多重数组，并未limit 1
 				//解开多重数组
 				foreach ($comm as $k ) {
 					//添加类别码供前端识别
 					$k["type"] = 'ac';
 					$res[] = $k;
 				}
 			}
 		}
 		if(empty($res))
 		{
 			return NULL;
 		}
 		else
 		{
 			return $res;
 		}
 	}

 	/**
 	 * 获取收藏电影的新评论
 	 * @param int userID 当前用户的ID
 	 * @return array 收藏电影的新评论
 	 */
 	private function _getFollowComment($userID)
 	{
 		//获取时间，转化为date形式
 		$date1 = date('Y-m-d H:i:s',$this->time);
 		$date2 = date('Y-m-d H:i:s',$this->_getTimestamp());
 	if($userID != NULL){
 		$mm = $this->_getFollow($userID);
 		$res = array();
 		foreach ($mm as $i) {
 			//注意between查询是从以前的时间到现在的时间
 			$sql = "select `id`,`author_id`,`time`,`content`,`movie_id` from `comment` where `movie_id`=:mid 
 					and time between :d2 and :d1";
 			try
 			{
 				$stmt = $this->db->prepare($sql);
 				$stmt->bindParam(':mid',$i['movie_id'],PDO::PARAM_INT);
 				$stmt->bindParam(':d1',$date1,PDO::PARAM_STR);
 				$stmt->bindParam(':d2',$date2,PDO::PARAM_STR);
 				$stmt->execute();
 				$comm = $stmt->fetchAll();
 				$stmt->closeCursor();
 			}
 			catch(Exception $e)
 			{
 				die($e->getMessage());
 			}
 			//如果没有新评论，pass
 			if(!empty($comm))
 			{
 				//注意返回的是多重数组，并未limit 1
 				//解开多重数组
 				foreach ($comm as $k ) {
 					//添加类别码供前端识别
 					$k["type"] = 'fc';
 					$res[] = $k;
 				}
 			}
 		}
 	}
 		else {
 			//注意between查询是从以前的时间到现在的时间
 			$sql = "select `id`,`author_id`,`time`,`content`,`movie_id` from `comment` where 
 					time between :d2 and :d1";
 			try
 			{
 				$stmt = $this->db->prepare($sql);
 				$stmt->bindParam(':d1',$date1,PDO::PARAM_STR);
 				$stmt->bindParam(':d2',$date2,PDO::PARAM_STR);
 				$stmt->execute();
 				$comm = $stmt->fetchAll();
 				$stmt->closeCursor();
 			}
 			catch(Exception $e)
 			{
 				die($e->getMessage());
 			}
 			//如果没有新评论，pass
 			if(!empty($comm))
 			{
 				//注意返回的是多重数组，并未limit 1
 				//解开多重数组
 				foreach ($comm as $k ) {
 					//添加类别码供前端识别
 					$k["type"] = 'fc';
 					$res[] = $k;
 				}
 			}
 		}
 		if(empty($res))
 		{
 			return NULL;
 		}
 		else
 		{
 			return $res;
 		}
 	}

 	/**
 	 * 获取关注人收藏新影片的信息
 	 * @param int userID
 	 * @return 收藏影片的记录
 	 */
 	private function _getAttenFollow($userID)
 	{
 		//获取时间，转化为date形式
 		$date1 = date('Y-m-d H:i:s',$this->time);
 		$date2 = date('Y-m-d H:i:s',$this->_getTimestamp());
 	if($userID != NULL){
 		$aa = $this->_getAttention($userID);
 		$res = array();
 		foreach ($aa as $i) {
 			$sql = "select `time`,`movie_id`,`user_id` from `follow` where `user_id`=:uid
 			 and time between :d2 and :d1";
			try
 			{
 				$stmt = $this->db->prepare($sql);
 				$stmt->bindParam(':uid',$i['attened_id'],PDO::PARAM_INT);
 				$stmt->bindParam(':d1',$date1,PDO::PARAM_STR);
 				$stmt->bindParam(':d2',$date2,PDO::PARAM_STR);
 				$stmt->execute();
 				$follow = $stmt->fetchAll();
 				$stmt->closeCursor();
 			}
 			catch(Exception $e)
 			{
	 			die($e->getMessage());
 			}
 			//如果没有新收藏，pass
 			if(!empty($follow))
 			{
 				//注意返回的是多重数组，并未limit 1
 				//解开多重数组
 				foreach ($follow as $k ) {
 					//添加类别码供前端识别
 					$k["type"] = 'af';
 					$res[] = $k;
 				}
 			}
 		}
 	}
 		else {
 			$sql = "select `time`,`movie_id`,`user_id` from `follow` where
 			 time between :d2 and :d1";
			try
 			{
 				$stmt = $this->db->prepare($sql);
 				$stmt->bindParam(':d1',$date1,PDO::PARAM_STR);
 				$stmt->bindParam(':d2',$date2,PDO::PARAM_STR);
 				$stmt->execute();
 				$follow = $stmt->fetchAll();
 				$stmt->closeCursor();
 			}
 			catch(Exception $e)
 			{
	 			die($e->getMessage());
 			}
 			//如果没有新收藏，pass
 			if(!empty($follow))
 			{
 				//注意返回的是多重数组，并未limit 1
 				//解开多重数组
 				foreach ($follow as $k ) {
 					//添加类别码供前端识别
 					$k["type"] = 'af';
 					$res[] = $k;
 				}
 			}
 		}
 		if(empty($res))
 		{
 			return NULL;
 		}
 		else
 		{
 			return $res;
 		}
 	}

	/**
	 *二维数组排序
	 * @param array $arr 需要排序的数组
	 * @param string $keys 排序依照的键值
	 * @param string $type 顺序(asc)or逆序(desc)
	 * @return array 排序好的数组
	 */
 	private function array_sort($arr, $keys, $type = 'desc')
 	{
    	$keysvalue = $new_array = array();
    	foreach ($arr as $k => $v) {
        	$keysvalue[$k] = $v[$keys];
    	}
    	if ($type == 'asc') {
        	asort($keysvalue);
    	} else {
        	arsort($keysvalue);
    	}
    	reset($keysvalue);
    	foreach ($keysvalue as $k => $v) {
        	$new_array[$k] = $arr[$k];
    	}
    	return $new_array;
	}

	/**
	 * 去掉关注人评论与收藏电影评论出现的重复的状态
	 * @return array 
	 */
	private function exAnd($arr)
	{
		$new = $temp = array();
		//foreach是拷贝一份数组出来迭代，不影响原数组
		foreach ($arr as $k) {
			//只需要去重ac和fc，所以af进入new
			if($k['type'] == 'af')
			{
				$new[] = $k;
			}
			else{
				$temp[] = $k;
			}
		}
		//将最后一个数组弹出，如果剩余数组有，则不管，没有则存入新数组
		foreach ($temp as $k) {
			$q = array_pop($temp);
			$del = 0;
			foreach ($temp as $k) {
				if($k['id'] == $q['id'])
				{
					$k['type'] = 'ac';
					$del = 1;
				}
			}
			if($del == 0)
			{
				$new[] = $q;
			}
		}
		return $new;
	}

 	/**
 	 * 将所有信息汇总并根据时间排序
 	 * 调用以上的方法
 	 * @param int userID 当前用户的ID
 	 * @return 所有动态按时间逆序排序的数组
 	 */
 	public function getNews()
 	{
 		$a = $this->_getAttenComment($this->userID);
 		$b = $this->_getFollowComment($this->userID);
 		$c = $this->_getAttenFollow($this->userID);
 		$all = array();
 		if(!empty($a))
 		{
 			$all = array_merge($all,$a);
 		}

 		if(!empty($b))
 		{
 			$all = array_merge($all,$b);
 		}

 		if(!empty($c))
 		{
 			$all = array_merge($all,$c);
 		}
 		$all = $this->exAnd($all);
 		return $this->array_sort($all,'time');
 	}

}

 ?>