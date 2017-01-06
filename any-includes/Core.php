<?php
if( !defined('IS_ANY') ) exit('Access denied!');

/**
 *	核心代码
 *	
 *	构建系统层代码耦合
 */

class Core{

	public static $parameter = [];

	public static $apps = [];

	private static $_widget;

	public static $cache;

	public function __construct(){

		header( "Content-type: text/html; charset=utf-8" );

		session_start();

		set_exception_handler( array( 'Core','exceptionHandle' ) );

		spl_autoload_register( array( 'Core', 'autoload' ) );

		date_default_timezone_set( 'PRC' );

		include_once ANYINC . 'Functions.php';

	}

	# 系统运行初始化
	public function init(){

		self::$parameter = include ANYINC.'Config.php';

		ini_set( 'display_errors', self::$parameter['debug'] ? 'On' : 'Off' );

		error_reporting( self::$parameter['debug'] ? E_ALL : 0 );

		self::$_widget = Widget::factory();

		self::$apps = self::getApps();

		# 全局数据缓存变量
		
		self::$cache = new Cache( ANYINC . 'cache/data/');

		self::_activateActions();

		return $this;
	}

	# 让应用小哥跑起来
	public function run(){

		Action::on( 'index:begin' );

		Route::dispatch( self::$apps );

		Action::on( 'index:end' );
	
	}

	/**
	 *	获取所有应用程序
	 *
	 *	@return array
	 */
	public static function getApps(){

		$apps_dir = glob( ANYAPP . '*' , GLOB_ONLYDIR );

		$apps = array_map( function( $dir_name ){

			return str_replace( ANYAPP,'',$dir_name );

		},$apps_dir );

		return $apps;

	}
	/**
	 *	文件自动加载
	 *
	 *	这里只引用系统层和第三方库文件，因为项目不大，用不着那些复杂的命名空间
	 *	只为简单快速开发而生。
	 *
	 *	@return array
	 */
	public static function autoload( $class ){

		$sys_file = ['Cache','DataBase','Action','UI','UIKit','Widget','Route','Router','Response'];

		$lib_dir = ANYINC . 'libraries' . DIRECTORY_SEPARATOR;

		$sys_dir = ANYINC . 'systems' . DIRECTORY_SEPARATOR;

		if( in_array( $class, $sys_file ) ){

			require_once $sys_dir.$class.'.php';

		}else{

			if( is_file( $lib_dir.$class.'.php' ) ){

				require_once $lib_dir.$class.'.php';
			
			}elseif ( is_file( $sys_dir.$class.'.php' ) ) {

				require_once $sys_dir.$class.'.php';
			
			}
		
		}

	}
	# 系统异常捕获
	public static function exceptionHandle( Exception $exception ){

		if( self::$parameter['debug'] ) self::_error( $exception );

	}
	private static function _error( $exception ){

		// 发送404信息

		header('HTTP/1.1 404 Not Found');

		header('Status:404 Not Found');

		if( is_object($exception) ){

			$line = $exception->getLine();

			$file = $exception->getFile();

			$message = $exception->getMessage();

			$content = $line.$file.$message;

			echo $content;
		}

	}
	# 激活所有已安装应用的默认动作
	private static function _activateActions(){

		$installed_apps = self::$_widget->appsInstalled();

		foreach ( $installed_apps as $key => $app ) {
		
			$action = ANYAPP .$app.DIRECTORY_SEPARATOR.'action.php';
		
			if(file_exists( $action )){
				
				require_once $action;
			
			}
		
		}
	
	}
}