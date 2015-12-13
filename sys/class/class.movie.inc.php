<?php

/**
 * 查询电影信息行为(2015-10-18)
 * PHP 5.6
 * @author dai@2015
 *
 */

class Movie extends DB_Connect
{

		//pagesize;分页的大小
		private $pagesize = 6;
		//pagecount;总页数
		public $pagecount = 0;
		/*
		 * 构造函数
		 * @param object $dbo 数据库对象，默认为mysql
		 */
		public function __construct($dbo=NULL)
		{
			//调用父类构造函数，生成数据库对象
			parent::__construct($dbo);
			//获取总页数
			$sql = "select `id` from `movie_info`" ;
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			//如果有余则加1，否则不加
			if(count($results)%$this->pagesize){
				$this->pagecount = (int)(count($results)/$this->pagesize) + 1;
			}
			else
			{
				$this->pagecount = (int)(count($results)/$this->pagesize);
			}
		}

		/**
		 * 查询电影信息
		 * @param string title 电影名
		 * @param int page 当前页码，对所有电影生成偏移量offset
		 * @return array 电影信息 
		 */
		public function findMoive($page=1 ,$title=NULL)
		{
			//根据pagesize和page计算偏移量
			$offset = ($page - 1) * $this->pagesize;

			if(!empty($title)){
				//查询单部电影 where + limit
				$sql = "select `id`,`title`,`director`,`writer`,`actor`,`runtime`,`another`,
					`types`,`country`,`lang`,`ontime`,`summary`,`average`,`votes` from `movie_info` where `title`=:title limit 1";
			}
			else{
				//查询所有电影
				$sql = "select `id`,`title`,`director`,`writer`,`actor`,`runtime`,`another`,
					`types`,`country`,`lang`,`ontime`,`summary`,`average`,`votes` from `movie_info`
					limit :pagesize offset :offset" ;
			}
			try
			{
				$stmt = $this->db->prepare($sql);
				/*
				 * 如果$title有效，则绑定到sql语句中，
				 * 该参数在execute()函数中生效，替换掉:title占位的参数
				 */

				if (!empty($title))
				{
					$stmt->bindParam(":title",$title,PDO::PARAM_STR);
				}
				else{
				//绑定pagesize和offset到sql语句中
				$stmt->bindParam(":pagesize",$this->pagesize,PDO::PARAM_INT);
				$stmt->bindParam(":offset",$offset,PDO::PARAM_INT);
				}
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();

				return $results;
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
		}


		/**
		 * 查询电影信息
		 * @param int id 电影数据库ID
		 * @return array 电影信息 
		 */
		public function findMoiveByID($id)
		{
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
				if (!empty($id))
				{
					$stmt->bindParam(":id",$id,PDO::PARAM_INT);
				}
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
			return $results;
		}


		/**
		 * 查找热门电影
		 * @return array 三部评论人最多的电影
		 */
		public function getHotMovie()
		{
			$sql = "select `title`,`director`,`actor`,`runtime`
					 from `movie_info` order by `votes` desc limit 3";
			try
			{
				$stmt = $this->db->prepare($sql);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();

				return $results;
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
		}
}

 ?>