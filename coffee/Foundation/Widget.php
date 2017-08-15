<?php

namespace Coffee\Foundation;

use Coffee\DataBase\DB;
use Coffee\Cache\Cache;

abstract class Widget
{
	public $app;

	public $db;

	public $cache;

	public $table = '';

	public function __construct($app = '')
	{

		$this->app = $app;

		$this->db = $this->_initDB();

		$this->cache = $this->_initCache();

		if( method_exists( $this, '_initialize' ) ) $this->_initialize();
	}

	private function _initDB()
	{
		static $_dbHandler;

		if($_dbHandler === null){
			$_dbHandler = (new DB( conf( 'database' ) ))->connect();
		}

		return $_dbHandler;
	}

	private function _initCache()
	{
		static $_cacheHandler;

		if($_cacheHandler === null){
			$_cacheHandler = Cache::init( conf( 'cache' ) );
		}

		return $_cacheHandler;
	}

	/**
	 *  设置操作数据库的表
	 *
	 *	@param string $table 表名称
	 *
	**/
	public function setTable($table)
	{
		$this->table = $table;

		return $this;
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
	 *	获取应用组件
	 *
	 * 	@param string $widget 组件名不区分大小写,调用子组件用@分隔，如"admin@api"
	 *	@return instance
	 */
	public static function get($widget)
	{
		if(!isset($widget)||empty($widget)) return false;

		$parts = (strpos($widget,'@')!==false) ? explode( '@', $widget ) : [ucfirst($widget)];

		$appNameSpace = '\\App\\'.ucfirst($parts[0]).'\\Widget\\';

		if( count($parts) > 1 ){
			$instance = $appNameSpace;
			foreach ($parts as $value) {
				$instance .= ucfirst($value);
			}

		}else{

			$instance = $appNameSpace.ucfirst($parts[0]);
		}
		return new $instance($parts[0]);
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
	 * 检查用户输入
	 *
	 * @param string $value 输入值
	 * @param string $rule 默认类型或正则表达式
	 * @return void
	 */
	public function validate($value,$rule)
	{

		$validate = [
			'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
			'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
			'currency'  =>  '/^\d+(\.\d+)?$/',
			'integer'   =>  '/^[-\+]?\d+$/',
			'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
			'english'   =>  '/^[A-Za-z]+$/',
			'chinese'	=>	'/^([\xE4-\xE9][\x80-\xBF][\x80-\xBF])+$/',
			'username'	=>	'/^[A-Za-z0-9_]+$/',
			'nickname'	=>	'/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9、。&]+$/u',
			'phone'		=>	'/^1[34578]{1}\d{9}$/',
			'qq'		=>	'/^[1-9]\d{4,12}$/',
		];

		if(isset($validate[$rule])) $rule = $validate[$rule];

		return preg_match( $rule,trim($value) );
	}

	/**
	 * 应用组件初始化回调
	 *
	 * @return void
	 */
	public function _initialize(){}
}