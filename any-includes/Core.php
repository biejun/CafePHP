<?php
if( !defined('ABSPATH') ) exit('Access denied!');
define( 'ANYSYSTEM' , ANYINC . 'systems'. DIRECTORY_SEPARATOR);
define( 'ANYLIB' , ANYINC .'libraries'. DIRECTORY_SEPARATOR);
define( 'APP_TIME' , $_SERVER['REQUEST_TIME']);
define( 'APP_VALIDATE' , md5(uniqid(rand(),TRUE)));

class Core{

	public static function init(){

		array_walk_recursive($_GET,'self::magic_safe');
		array_walk_recursive($_POST,'self::magic_safe');
		array_walk_recursive($_REQUEST,'self::magic_safe');

		$_REQUEST = array_merge( $_GET, $_POST);

		error_reporting(ANY_DEBUG?E_ALL:0);
		ini_set('display_errors',ANY_DEBUG?'On':'Off');
		spl_autoload_register('self::autoload');
		set_exception_handler('self::error_tip');

		# 执行安装
		if(!file_exists( ANYINC .'any-config.php'))
			return include( ANYINC . 'any-install.php');

		# 加载配置文件
		require_once( ANYINC . 'any-config.php');

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
# 取得多个$_GET或$_POST参数的值
function query_vars( $vars ) {
	foreach ((array) $vars as $var ) {
		if ( empty( $_POST[ $var ] ) ) {
			if ( empty( $_GET[ $var ] ) ) {
				$vars[ $var ] = '';
			} else {
				$vars[ $var ] = $_GET[ $var ];
			}
		} else {
			$vars[ $var ] = $_POST[ $var ];
		}
	}
	return $vars;
}
# 取得$_GET参数的值
function get_query_var( $var , $def = ''){
	if( isset( $_GET[$var] ) ){
		return is_int($def) ? (int) $_GET[$var] : trim($_GET[$var]);
	}
	return $def;
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
	if (is_object($mix)) {
		return spl_object_hash($mix);
	} elseif (is_resource($mix)) {
		$mix = get_resource_type($mix) . strval($mix);
	} else {
		$mix = serialize($mix);
	}
	return md5($mix);
}
/**
 * $_SESSION操作
 * @param string $key session名称 如果为空则取出全部session
 * @param mixed $value session值 如果为空则取出值 为null则删除
 * @return mixed
 */
function session( $key , $value = ''){
	if( '' === $key ) return $_SESSION;
	if( '' === $value ){
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}elseif(is_null($value)){
		unset($_SESSION[$key]);
	}else{
		$_SESSION[$name]  =  $value;
	}
}
# 调用应用程序数据模型
function model($app='',$name='class'){
	static $_model = array();
	if(empty($app)){
		if(isset($_model['model'])){
			return $_model['model'];
		}else{
			$_model['model'] = new Model($app);
			return $_model['model'];
		}
	}
	$attribute = ('class'==$name)?'model':$name;
	$instance = $app.'_'.$attribute;
	if(isset($_model[$instance]))
		return $_model[$instance];
	if(is_file( ANYAPP .$app.'/'.$app.'.'.$name.'.php')){
		require( ANYAPP .$app.'/'.$app.'.'.$name.'.php');
		$_model[$instance] = new $instance($app);
		return $_model[$instance];
	}else{
		throw new Exception('没有找到"'.$app.'"的"'.$file.'"文件');
	}
}