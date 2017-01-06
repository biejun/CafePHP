<?php

/**
 *	ANYPHP V 2.0
 *	----------------------------------
 *	对V1.2.0版本进行了巨大的改进
 */

if( version_compare( PHP_VERSION , '5.4.0' , '<' ) ) die( 'PHP VERSION >= 5.4.0 !' );

// 系统目录

define( 'ABSPATH' , dirname(__FILE__).DIRECTORY_SEPARATOR );

define( 'ANYAPP' , ABSPATH . 'any-apps' . DIRECTORY_SEPARATOR );

define( 'ANYTHEME' , ABSPATH . 'any-themes' . DIRECTORY_SEPARATOR );

define( 'ANYINC' , ABSPATH . 'any-includes' . DIRECTORY_SEPARATOR );

// 系统变量

define( 'IS_ANY' , true );

define( 'VERSION' , '2.0.0' );

if ( !is_file(ANYINC . 'Config.php' ) ) {

	file_exists('./install.php') ? header('Location: install.php') : print('请上传安装文件!');
	exit;
}

// 引入核心

require ANYINC . 'Core.php';

$app = new Core();

$app->init()->run();