<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *	数据模型类
 *
 *	构建数据逻辑与视图分离
 */
class Model{

	protected $app;

	protected $db = null;

	private $cache;

	private $open_cache = false;

	public function __construct($app=''){

		if(method_exists($this, '_initialize'))
			$this->_initialize();
		$this->app = $app;
		$this->db = self::db_connect();
		$this->cache = $this->cache_conf();
	}
	private static function db_connect(){
		static $_db = null;
		if($_db) return $_db;
		$_db = new DB( DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, DB_PREFIX );
		return $_db;
	}
	private function cache_conf(){
		if(empty($this->app)) return false;
		static $_cache = array();
		if($this->open_cache){
			if(isset($_cache[$this->app])) return $_cache[$this->app];
			$cache_path = ANYAPP . $this->app . '/cache/';
			$_cache[$this->app] = new Cache($cache_path);
			return $_cache[$this->app];
		}
	}
	public function get_app_value($app){
		if(empty($app)) return false;
		$value = $this->db->val("config","config_value","config_key='$app'");
		return (empty($value))?array():$value;
	}
	# 获取app安装列表
	public function get_app_lists(){
		global $cache;
		$apps = $cache->read('apps');
		if(!$apps){
			$value = $this->get_app_value('apps');
			if(!empty($value)){
				$apps = explode("|",$value);
				$cache->write('apps',$apps);
			}
		}
		return $apps;
	}
	# 获取应用配置信息
	public function get_app_config($app=''){
		global $cache;
		if(empty($app)) $app = $this->app;
		$config = $cache->read($app.'_config');
		if(!$config){
			$value = $this->get_app_value($app);
			if(!empty($value)){
				$value = unserialize(base64_decode($value));
				$config = array_merge((array) $config,$value);
				unset($value);
				$cache->write($app.'_config',$config);
			}
		}
		return $config;
	}
	# 写入应用配置信息
	public function set_app_config($data,$app=''){
		global $cache;
		if(empty($data)) return false;
		if(empty($app)) $app = $this->app;
		$data=base64_encode(serialize($data));
		$this->db->update("config",array('config_value'=>$data),"config_key='".$app."'");
		$cache->delete_cache($this->app.'_config');
		return true;
	}
	public function get_theme(){
		global $cache;
		$theme = $cache->read('theme');
		if(!$theme){
			$theme = $this->get_app_value('theme');
			$cache->write('theme',$theme);
		}
		return $theme;
	}
	public function insert_table($table,$data){
		$this->db->insert($table,$data);
		return $this->db->id();
	}
	public function __set($name, $value){
		$this->$name = $value;
	}
	# 获取数据库版本
	public function get_db_version(){
		return $this->db->version();
	}
	# 初始化回调
	public function _initialize(){}
}