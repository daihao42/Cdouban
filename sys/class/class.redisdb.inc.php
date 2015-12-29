<?php 
/**
 * redis封装，基本操作(set 增加，查询，求集合等)
 * copyright@2015 by dai
 * PHP 5.6
 */

/*********
 * db0作为存放type，ontimes和country的筛选的持久层
 * db1将作为recommend的缓冲层，取出所有数据放入缓冲层
 *
 ***********/

class Redisdb
{

	//保存取出的所有数据的数组，注意，在get_page_Union后才赋值
	private $type_arr;

	protected $redis;
	/**
	 * 连接redis，localhost
	 * @param dbn 数据库编号
	 */
	public function __construct($dbn)
	{
		$this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->select($dbn);
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

	/**
	 * incr原子性自增,用于处理访问量等
	 * incr(key) return 自增后的值
	 * @param string 自增的键值
	 */
	public function incr($key)
	{
		return $this->redis->incr($key);
	}

	/**
	 * 设置键值
	 * 默认为0
	 */
	public function setKey($key,$val=0)
	{
		return $this->redis->set($key,$val);
	}

	/**
	 *
	 *
	 */
	public function getKey($key)
	{
		return $this->redis->get($key);
	}

	/**
	 * 分页查询
	 * 使用PHP的数组分片
	 */
	public function get_page_Union($page,$types){
		$offset = ($page - 1)*6;
		$this->type_arr = $this->getUnion($types);
		return array_slice($this->type_arr,$offset,6);
	}

	/**
	 * 获取总页数
	 */
	public function get_page_Count(){
		if(count($this->type_arr) % 6){
				return (int)(count($this->type_arr) / 6 + 1);
			}
			else
			{
				return (int)(count($this->type_arr) / 6 );
			}
	}

}

 ?>
