<?php
if( !defined('IS_ANY') ) exit('Access denied!');

class Widget{

	public $db;

	public $config = [];

	public $app;

	public $cache;	// 应用数据缓存

	public $table = 'config';

	private static $_widgetPool = array(); // 组件池

	private static $_instance = null;

	public $activate_cache = false; // 缓存是否开启
	
	private function __construct( $app = '' ){

		// 保存当前实例化的应用

		$this->app = $app;

		// 全局配置参数赋值给当前类的config变量

		$this->config = &Core::$parameter;

		$this->db = $this->_dbAdapter();

		$this->cache = $this->tempCache($this->app,$this->activate_cache);

		if( method_exists( $this, '_initialize' ) ) $this->_initialize();
	
	}

	/**
	 *	数据库适配器
	 *
	 *	@return DataBase
	 */
	private function _dbAdapter(){

		static $_db = NULL;

		if( NULL === $_db && isset( $this->config['db'] ) ){

			$db_config = $this->config['db'];

			$_db = DataBase::factory( $db_config );
		}

		return $_db;

	}

	/**
	 *	数据本地缓存
	 *
	 *	@return Cache
	 */
	private static function tempCache($app,$activate_cache = false){
		
		if(empty($app)) return false;
		
		static $_cache = array();
		
		if($activate_cache){
		
			if(isset($_cache[$app])) return $_cache[$app];
		
			$cache_path = ANYAPP . $app . '/cache/';
		
			$_cache[$app] = new Cache($cache_path);
		
			return $_cache[$app];
		}
	}

	/**
	 *	实例化组件对象
	 *
	 *	@return Widget
	 */
	public static function factory(){

		return ( self::$_instance ) ? self::$_instance : new Widget();
	
	}
	/**
	 *	获取应用组件
	 *	
	 * 	@param string $widget 组件名 如 'admin' , 调用子组件用@分隔 'admin@api'
	 *	@return instance
	 */
	public static function get( $widget ){

		if( !isset($widget) ) return false;

		$widget = strtolower( $widget );

		if( strpos($widget,'@')!==false ){
		
			$parts = explode( '@', $widget );

			$instance = ucfirst($parts[0]).ucfirst($parts[1]).'Widget';
		
		}else{

			$parts = array( $widget , 'widget' );

			// 应用组件类名命名规范：AdminWidget,单词首字母必须大写

			$instance = ucfirst($widget).'Widget';
		}

		// 组件对象池里是否已存在

		if( isset( self::$_widgetPool[$instance] ) ) return self::$_widgetPool[$instance];

		// 从应用程序的widgets文件夹中找到当前需要实例化的组件文件
		
		$widget_file = ANYAPP .$parts[0].'/widgets/'.$instance.'.php';
		
		if( is_file( $widget_file ) ){
		
			require( $widget_file );

			if( class_exists( $instance ) ){

				self::$_widgetPool[$instance] = new $instance( $parts[0] );
			
				return self::$_widgetPool[$instance];
			}
		
		}else{
		
			throw new Exception('没有找到应用['.$parts[0].']的['.$parts[1].']组件');
		}
	}
	/**
	 * 复制当前组件到一个新变量中
	 *
	 * @param string $variable 变量名
	 * @return void
	 */
	public function to( &$variable ){
		
		return $variable = $this;
	}
	/**
	 * 检查用户输入
	 *
	 * @param string $value 输入值
	 * @param string $rule 默认类型或正则表达式
	 * @return void
	 */
	public function checkInput( $value , $rule ){

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
	 *	获取已安装的应用
	 *
	 *	@return array
	 */
	public function appsInstalled(){

		$cache = Core::$cache; // 全局缓存

		$apps = $cache->read('apps');
		
		if(!$apps){
		
			$value = $this->getAppValue('apps');
		
			if(!empty($value)){
		
				$apps = explode("|",$value);
		
				$cache->write('apps',$apps);
			}
		}
		return $apps;
	}
	public function getAppValue($app){
		
		if(empty($app)) return false;
		
		$value = $this->db->one($this->table,"config_value","config_key='$app'");
		
		return (empty($value))?array():$value;
	}
	# 获取主题
	public function getThemeName(){
		
		$cache = Core::$cache;
		
		$theme = $cache->read('theme');
		
		if(!$theme){
		
			$theme = $this->getAppValue('theme');
		
			$cache->write('theme',$theme);
		}
		return $theme;
	}
	# 获取应用配置信息
	public function getAppConfig($app=''){
		
		$cache = Core::$cache;
		
		if(empty($app)) $app = $this->app;
		
		$config = $cache->read($app.'_config');
		
		if(!$config){
		
			$value = $this->getAppValue($app);
		
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
	public function setAppConfig( $data,$app='' ){
		
		$cache = Core::$cache;
		
		if(empty($data)) return false;
		
		if(empty($app)) $app = $this->app;
		
		$data=base64_encode(serialize($data));
		
		$this->db->update($this->table,array('config_value'=>$data),"config_key='".$app."'");
		
		$cache->deleteCache($this->app.'_config');

		return true;
	}
	# 获取数据库版本
	public function getDbVersion(){

		return $this->db->version();
	}
	# 插入数据到一个表中，返回新ID
	public function insertTable($table,$data){

		$this->db->insert($table,$data);
		return $this->db->id();
	}
	/**
	 * 应用组件初始化回调
	 *
	 * @return void
	 */
	public function _initialize(){}
}