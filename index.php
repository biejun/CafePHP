<?php
/**
 *	ANYPHP
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
error_reporting( ANY_DEBUG ? E_ALL : 0);
ini_set('display_errors', ANY_DEBUG ? 'On' : 'Off' );

require ANYINC . 'Core.php';

# 初始化 默认首页
Route::get('admin');
