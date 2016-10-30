<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *	应用程序组件类
 *
 */
class Widget{

	private $app;

	protected $db = null;

	public static $_installed = array();

	public $cache;

	public $activate_cache = false;

	public $table = 'config';

	public function __construct($app=''){
		
		if(method_exists($this, '_initialize'))
			$this->_initialize();

		$this->app = $app;
		$this->db = self::db_connect();
		$this->cache = self::cache_config($this->app,$this->activate_cache);
		
		if(empty(self::$_installed)) self::$_installed = $this->get_app_lists();
	}
	private static function db_connect(){
		static $_db = null;
		if($_db) return $_db;
		$_db = DB::factory( DB_HOST, DB_NAME, DB_USER, DB_PASSWORD , DB_PREFIX,DB_LIB);
		return $_db;
	}
	private static function cache_config($app,$activate_cache = false){
		if(empty($app)) return false;
		static $_cache = array();
		if($activate_cache){
			if(isset($_cache[self::$app])) return $_cache[$app];
			$cache_path = ANYAPP . $app . '/cache/';
			$_cache[$app] = new Cache($cache_path);
			return $_cache[$app];
		}
	}
	# 激活已安装应用的插件
	public function activate_actions(){
		foreach (self::$_installed as $key => $app) {
			$plugin = ANYAPP .$app.'/plugin.php';
			if(file_exists($plugin))
				include $plugin;
		}
	}
	public function get_app_value($app){
		if(empty($app)) return false;
		$value = $this->db->one($this->table,"config_value","config_key='$app'");
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
		$this->db->update($this->table,array('config_value'=>$data),"config_key='".$app."'");
		$cache->delete_cache($this->app.'_config');
		return true;
	}
	public function get_apps_config(){
		$array = array();
		foreach (self::$_installed as $app) {
			$config = $this->get_app_config($app);
			if(!empty($config))
				$array = array_merge($array,$config);
		}
		return $array;
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
	# 插入数据到一个表中，返回新ID
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