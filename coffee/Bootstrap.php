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

/* 定义系统请求响应缓存时间 */
define( 'EXPIRES', 0 );

/* 定义一个随机HASH码 */
define( 'HASH', md5( uniqid(rand(), true) ));

if(!defined('PATH')) {

    /* 伪静态配置 */
    if(PHP_SAPI === 'apache2handler')
    {
        $path = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
        if(!file_exists('.htaccess')){
            $file = fopen('.htaccess', 'wb');
            fwrite($file, "<IfModule mod_rewrite.c>\n".
                "  Options +FollowSymlinks\n".
                "  RewriteEngine On\n".
                "  RewriteBase {$path}\n".
                "  RewriteCond %{REQUEST_FILENAME} !-d\n".
                "  RewriteCond %{REQUEST_FILENAME} !-f\n".
                "  RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]\n".
                "</IfModule>");
            fclose($file);
        }
    }
    else if(PHP_SAPI === 'fpm-fcgi' || PHP_SAPI === 'cgi-fcgi')
    {
        $path = str_replace('index.php','',
            rtrim('/'.ltrim($_SERVER['SCRIPT_NAME'], '/'), '/'));

        if(!file_exists('nginx.config')){
            $file = fopen('nginx.config', 'wb');
            fwrite($file, "location {$path} {\n".
                "  if (-f \$request_filename/index.php){\n".
                "    rewrite (.*) $1/index.php;\n".
                "  }\n".
                "  if (!-f \$request_filename){\n".
                "    rewrite (.*) /index.php;\n".
                "  }\n".
            "}");
            fclose($file);
        }
    }

    /* 定义站点根目录 */
    define('PATH', $path);
}

/* 载入系统模块加载器 */
require __DIR__  . '/Loader.php';

/* 注册系统模块加载器 */
\Coffee\Loader::register();

(new \Coffee\Foundation\Container)->run();