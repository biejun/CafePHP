<?php
/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.0.6
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

/* 定义系统绝对路径 (Non modifiable) */
define( 'ABSPATH', realpath(__DIR__ . '/..') . '/' );

/* 定义系统核心层目录 (Non modifiable) */
define( 'CORE', ABSPATH . 'Coffee' );

/* 定义系统应用层目录 (Non modifiable) */
define( 'APP', ABSPATH . 'App' );

/* 定义系统配置目录 */
define( 'CONFIG', ABSPATH . 'Config' );

/* 定义系统前端文件目录 */
define( 'VIEW', ABSPATH . 'View' );


/**
 * 定义系统运行环境
 *
 * 0 生产环境 1 开发环境
 */
define( 'IS_DEVELOPMENT', 0 );

/* 定义系统时区 */
define( 'TIMEZONE', 'PRC' );

/* 定义系统默认字符编码集 */
define( 'CHARSET', 'UTF-8' );

/* 定义站点根目录 */
define( 'PATH' , str_replace('index.php','',$_SERVER['SCRIPT_NAME']) );


/* 载入系统模块加载器 */
require __DIR__  . '/Loader.php';

/* 注册系统模块加载器 */
\Coffee\Loader::register();

(new \Coffee\Foundation\Container)->run();