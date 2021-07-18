<?php
/**
 * Cafe PHP
 *
 * This is a base configuration file that
 * defines the constants of a system.
 *
 * 这是一个用于定义系统常量的配置文件，你可以
 * 对其中一些配置进行修改。
 *
 */

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
/**
 * 定义系统运行环境
 *
 * 0 生产环境 1 开发环境
 *
 * 部署到生产环境后请务必将此处修改为 0
 */
define( 'IS_DEVELOPMENT', 1 );
/* 定义系统默认字符编码集 */
define( 'CHARSET', 'UTF-8' );
/* 定义系统请求响应缓存时间 */
define( 'EXPIRES', 0 );
/* 定义一个随机令牌 */
define( 'TOKEN', md5( uniqid(rand(), true) ));
/* 数据加密密钥 (Non modifiable) */
define( 'HASH', '+|G^fe~< *tffy1lTs02>Leq/r5l7{zl' );