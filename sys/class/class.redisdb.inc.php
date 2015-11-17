<?php 
/**
 * redis封装，基本操作(set 增加，查询，求集合等)
 * copyright@2015 by dai
 * PHP 5.6
 */

class Redisdb
{

	protected $redis;
	/**
	 * 连接redis，localhost
	 * @param dbn 数据库编号
	 */
	public function __construct($dbn)
	{
		$this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379, $dbn);
	}

	/**
	 * 查询set的数据
	 * @param string $type set名
	 * @return array set数据数组
	 */
	public function getType($type)
	{
		return $this->redis->sMembers($type);
	}

	/**
	 * 获取多类型交集
	 * @param array $types 多类型数组
	 * @return array set数组数据集合
	 */
	public function getUnion($types)
	{
		//将第一种类型的数据作为并集的基数
		//若取新空数组则并集将一直为空
		//print_r(each($types));
		$arr = $this->getType(each($types)[1]);
		foreach ($types as $k) {
			$arr = array_intersect($arr,$this->getType($k));
		}
		return $arr;
	}

}

 ?>
