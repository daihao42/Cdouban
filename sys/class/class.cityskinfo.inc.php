<?php
/*
 * PHP 5.6
 * 天气的信息数据封装类（2015-10-13）
 * @author dai@2015
 * 
 */
 class CitySkInfo{
 	public $city;
 	public $temp;
 	public $windfrom;
 	public $winddegree;
 	public $dampness;
 	public $njd;
 	public $qy;
 	public $updatetime;

 	/*
 	 * @param array $cityinfo 城市天气的信息
 	 */
 	public function __construct($cityinfo)
 	{
 		if(is_array($cityinfo))
 		{
 			$this->city = $cityinfo['city'];
 			$this->temp = $cityinfo['temp'];
 			$this->windfrom = $cityinfo['windfrom'];
 			$this->winddegree = $cityinfo['winddegree'];
 			$this->dampness = $cityinfo['dampness'];
 			$this->njd = $cityinfo['njd'];
 			$this->qy = $cityinfo['qy'];
 			$this->updatetime = $cityinfo['updatetime'];
 		}
 		else
 		{
 			throw new Exception("Error without cityinfo!");
 		}
 	}
 }
?>