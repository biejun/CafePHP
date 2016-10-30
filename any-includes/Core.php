<?php
if( !defined('ABSPATH') ) exit('Access denied!');
/**
 *	系统核心
 *
 */

# 定义常量
define( 'ANYSYSTEM' , ANYINC .'systems'. DIRECTORY_SEPARATOR);
define( 'ANYWIDGET' , ANYINC .'widgets'. DIRECTORY_SEPARATOR);
define( 'VERSION' , 'V 1.2.0 161023');
define( 'APP_TIME' , $_SERVER['REQUEST_TIME']);
define( 'APP_VALIDATE' , md5(uniqid(rand(),TRUE)));

# 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('PHP_VERSION > 5.3.0 !');

@ob_start("ob_gzhandler");

session_start();

include( ANYINC . 'Functions.php' );

# 设置时区
if(version_compare(PHP_VERSION,'5.1.0','>')){
	date_default_timezone_set('PRC');
}
# PHP 5.4 以上版本忽略此重置
if(version_compare(PHP_VERSION,'5.4.0','<')){
	ini_set( 'magic_quotes_runtime', 0 );
	ini_set( 'magic_quotes_sybase',  0 );
}
# 错误捕获
function exception_handle($e){
	// $e->getLine();
	// $e->getFile();
	echo 'Tip: ',$e->getMessage();
}
# 自动加载系统类库
function autoload($class){
	if(is_file( ANYSYSTEM . $class . '.php')){
		require_once( ANYSYSTEM . $class . '.php');
	}elseif(is_file( ANYWIDGET . $class . '.php')){
		require_once( ANYWIDGET . $class . '.php');
	}
}
# 递归去掉反斜线
function stripslashesDeep( &$value){
	$value = stripslashes($value);
}

header('Content-Type: text/html; charset=utf-8');

if (function_exists('spl_autoload_register')) {
	spl_autoload_register('autoload');
}else{
	function __autoLoad($class){
		autoload($class);
	}
}

set_exception_handler('exception_handle');

if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()){
	array_walk_recursive( $_GET ,'stripslashesDeep');
	array_walk_recursive( $_POST ,'stripslashesDeep');
	array_walk_recursive( $_COOKIE ,'stripslashesDeep');
}

# 执行安装
if(!file_exists( ANYINC .'Config.php' ))
	exit(include( ANYINC . 'Install.php' ));

# 加载配置文件
require( ANYINC . 'Config.php' );

# 全局数据缓存变量
$cache = new Cache( ANYINC . 'cache/data/');

# 激活动作
widget()->activate_actions();