<?php
/*
 * PHP 5.6
 * 天气的数据封装类（2015-10-13）
 * @author dai@2015
 * 
 */


	class Weather extends DB_Connect
	{
		private $userCity;
		/*
		 * 构造函数
		 * @param object $dbo 数据库对象，默认为mysql
		 * @param string $userCity 城市名称（或ID）
		 */
		public function __construct($dbo=NULL,$userCity=NULL)
		{
			//调用父类构造函数，生成数据库对象
			parent::__construct($dbo);
			$this->userCity=$userCity;
		}

		/*
		 * _loadWeatherData从数据库中获取天气信息
		 * @param string city 查询天气的城市
		 * @return array 查询数据库返回的结果数组 
		 */
		private function _loadWeatherData()
		{
			if(!empty($this->userCity)){
				//查询单个城市 where + limit
				$sql = "select `temp`,`windfrom`,`winddegree`,`dampness`,
					`njd`,`qy`,`updatetime`,`city` from `city_sk_info` where `city`=:city limit 1";
			}
			else{
				//查询所有城市
				$sql = "select `temp`,`windfrom`,`winddegree`,`dampness`,
					`njd`,`qy`,`updatetime`,`city` from `city_sk_info`";
			}

			try
			{
				$stmt = $this->db->prepare($sql);
				/*
				 * 如果$city有效，则绑定到sql语句中，
				 * 该参数在execute()函数中生效，替换掉:city占位的参数
				 */

				if (!empty($this->userCity))
				{
					$stmt->bindParam(":city",$this->userCity,PDO::PARAM_STR);
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

		/*
		 * 生成天气信息数组
		 * @return array CityInfo obj 城市天气信息
		 */
		private function __createCityObj()
		{
			//载入天气信息
			$arr = $this->_loadWeatherData();

			//生成天气信息对象数组，目前看来是多余的(2015-10-13)
			$citysk = array();
			$i=0;
			foreach ($arr as $sk) {
				$citysk[$i++] = new CitySkInfo($sk);
			}
			return $citysk;
		}

		/*
		 * 生成前端显示数据前对数据进行html处理
		 * @return $html 处理过的用于显示的html
		 */
		public function buildCityInfo()
		{
			$citysk = $this->__createCityObj();
			/*
			$html = "<h2>天气汇报</h2>";
			foreach ($citysk as $arr) {
				# code...
				$ls = sprintf("\n\t<ul><li>")
				$html .= "\n\t<ul><li></li><ul>";
			}
			return $html;
			*/
			return $citysk;
		}



 	/*
 	 * 获取用户城市
 	 * @param string uname 用户名
 	 * @return string city 用户城市
 	 */
 	public function getUserCity($uname)
 	{
 		/*
 		 * 若用户存在则返回数据库中匹配信息
 		 */
 		$sql = "select `user_city`
 				from `users` where `user_name` = :uname limit 1";
 		try
 		{
 			$stmt = $this->db->prepare($sql);
 			$stmt->bindParam(':uname',$uname,PDO::PARAM_STR);
 			$stmt->execute();
 			$city = $stmt->fetchAll();
 			$stmt->closeCursor();
 		}
 		catch(Exception $e)
 		{
 			die($e->getMessage());
 		}
 		return $city;
 	}
	}

?>