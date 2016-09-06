<?php
if( !defined('ABSPATH') ) exit('Access denied!');

define( 'APP_TIME' , $_SERVER['REQUEST_TIME']);
define( 'APP_VALIDATE' , md5(uniqid(rand(),TRUE)));

error_reporting(ANY_DEBUG?E_ALL:0);
ini_set('display_errors',ANY_DEBUG?'On':'Off');
set_exception_handler('error_tip');

define( 'ANYSYSTEM' , ANYINC . 'systems/');
define( 'ANYLIB' , ANYINC .'libraries/');

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
# 魔法函数的安全过滤
function magic_safe( $array ){
	foreach ( (array) $array as $key => $var) {
		if((strtoupper($key) != $key || ''.intval($key) == "$key") && $key != 'argc' && $key != 'argv'){
			if(is_array($var)){
				$array[$key] = magic_safe($var);
			}else{
				if(!function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()){
					$array[$key] = addslashes($var);
				}else{
					$array[$key] = $var;
				}
			}
		}
	}
	return $array;
}
# 防御XSS攻击
function xss_clean( $data ) {
	$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
	 '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
	 '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
	do {
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|iframe|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	} while ($old_data !== $data);
	return $data;
}
# 获取$_SESSION
function session( $key ){
	return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
}
# 设置$_SESSION
function set_session( $key , $value ){
	$_SESSION[$key] = $value;
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
# 自动加载系统类
function autoload($class){
	if(is_file( ANYSYSTEM . $class . '.php'))
		require_once( ANYSYSTEM . $class . '.php');
}
function error_tip($e){
	// $e->getLine();
	// $e->getFile();
	echo 'Tip: ',$e->getMessage();
}