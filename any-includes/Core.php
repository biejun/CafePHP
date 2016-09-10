<?php
if( !defined('ABSPATH') ) exit('Access denied!');
define( 'ANYSYSTEM' , ANYINC . 'systems'. DIRECTORY_SEPARATOR);
define( 'ANYLIB' , ANYINC .'libraries'. DIRECTORY_SEPARATOR);
define( 'APP_TIME' , $_SERVER['REQUEST_TIME']);
define( 'APP_VALIDATE' , md5(uniqid(rand(),TRUE)));

include(ANYINC.'Functions.php');

class Core{

	public static function init(){

		array_walk_recursive($_GET,'self::magic_safe');
		array_walk_recursive($_POST,'self::magic_safe');
		
		$_REQUEST = array_merge( $_GET, $_POST);

		error_reporting(ANY_DEBUG?E_ALL:0);
		ini_set('display_errors',ANY_DEBUG?'On':'Off');
		spl_autoload_register('self::autoload');
		set_exception_handler('self::error_tip');

		# 执行安装
		if(!file_exists( ANYINC .'Config.php'))
			exit(include( ANYINC . 'Install.php'));

		# 加载配置文件
		require_once( ANYINC . 'Config.php');

	}
	private static function error_tip($e){
		// $e->getLine();
		// $e->getFile();
		echo 'Tip: ',$e->getMessage();		
	}
	# 自动加载系统类库
	private static function autoload($class){
		if(is_file( ANYSYSTEM . $class . '.php'))
			require_once( ANYSYSTEM . $class . '.php');
	}
	# 魔法函数的安全过滤
	private static function magic_safe( &$value){
		if(!function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()){
			$value = addslashes($value);
		}
		// 过滤查询特殊字符
		if(preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i',$value)){
		    $value .= ' ';
		}
	}
}