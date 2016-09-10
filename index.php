<?php
/**
 *	AnyPHP
 *
 *	@version 1.00
 *	@copyright www.anyjs.org
 *	@since  2016.04.06
 */

# 定义系统路径
define( 'ABSPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
define( 'ANYAPP',ABSPATH . 'any-apps' . DIRECTORY_SEPARATOR);
define( 'ANYTHEME',ABSPATH . 'any-themes' . DIRECTORY_SEPARATOR);
define( 'ANYINC',ABSPATH . 'any-includes' . DIRECTORY_SEPARATOR);

# 生产模式 开启：true 关闭: false
define( 'ANY_DEBUG', true);

# 设置时区
date_default_timezone_set('PRC');

# PHP 5.4 以上版本忽略此重置
if(version_compare(PHP_VERSION,'5.4.0','<')){
	ini_set( 'magic_quotes_runtime', 0 );
	ini_set( 'magic_quotes_sybase',  0 );
}

require_once( ANYINC . 'Core.php' );

session_start();

ob_start( 'ob_gzhandler' );

header('Content-Type: text/html; charset=utf-8');

$timestart = microtime( true );

# 系统核心初始化
Core::init();

# 全局数据缓存变量
$cache = new Cache(ANYINC . 'cache/data/');

# 初始化 默认首页
Route::get('admin');

echo microtime( true ) - $timestart ;
