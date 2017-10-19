<?php
/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.0.6
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

/**
 * 系统应用层组件核心代码
 *
 * @package Coffee\Foundation\Component
 * @since 0.0.5 所有应用数据操作基于此类
 */
namespace Coffee\Foundation;

use Coffee\DataBase\DB;
use Coffee\Cache\Cache;

abstract class Component
{

	public $db;

	public $database = null;

	public $created = false;

	public $table = '';

	public function __construct()
	{

		$this->db = $this->initDB();

		$this->db->connect($this->database,$this->created);

		if( !empty($this->table) ) $this->db->from($this->table);

		if( method_exists( $this, '_initialize' ) ) $this->_initialize();
	}

	private function initDB()
	{
		static $_connect;

		if(!isset($_connect)){
			$_connect = new DB(G('database'));
		}
		return $_connect;
	}

	/**
	 *  执行应用组件中的方法
	 *
	 *	@param string $func 回调函数
	 *  @param array $args 回调函数参数
	 *  @return mixed
	 *
	**/
	public function run($func, $args)
	{
		$reflection = new \ReflectionClass($this);
		$parentClass = $reflection->getParentClass();

		if($parentClass){

			$parentMethods = $parentClass->getMethods();
			// 过滤父类方法
			while ($it = current($parentMethods)) {
				if($func === $it->getName()){
					return false;
				}else{
					next($parentMethods);
				}
			}
			if($reflection->hasMethod($func)){
				$method = $reflection->getMethod($func);
				if($method->isPublic()){
					$params = [];
					foreach ($method->getParameters() as $arg) {
						if($args[$arg->name]){
							$params[$arg->name] = $args[$arg->name];
						}else{
							$params[$arg->name] = null;
						}
					}
					return $method->invokeArgs($this,$params);
				}
			}
		}
		return false;
	}

	/**
	 *	实例化一个应用组件
	 *
	 * 	@param string $component 组件名不区分大小写,调用子组件用@分隔，如"admin@api"
	 *	@return instance
	 */
	public static function instance($component)
	{
		if(empty($component)) return false;

		$parts = (strpos($component,'@')!==false) ? explode( '@', $component ) : [ucfirst($component)];

		$app = array_shift($parts);

		$appNameSpace = '\\App\\'.ucfirst($app).'\\Components\\';

		if( count($parts) > 0 )
		{
			$className = $appNameSpace;

			foreach ($parts as $value)
			{
				$className .= ucfirst($value);
			}
		}
		else
		{
			$className = $appNameSpace.ucfirst($app);
		}
		return new $className();
	}

	/**
	 * 复制当前组件到一个新变量中
	 *
	 * @param string $variable 变量名
	 * @return void
	 */
	public function to(&$variable)
	{

		return $variable = $this;
	}

	/**
	 * 应用组件初始化回调
	 *
	 * @return void
	 */
	public function _initialize(){}
}