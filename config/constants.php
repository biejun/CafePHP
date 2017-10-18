<?php
/**
 * AnyPHP Coffee
 *
 * This is a base configuration file that
 * defines the constants of a system.
 *
 * 这是一个用于定义系统常量的配置文件，你可以
 * 对其中一些配置进行修改。
 *
 */

/* 定义系统绝对路径 (Non modifiable) */
define( 'ABSPATH', realpath(__DIR__ . '/..') . '/' );

/* 定义系统核心层目录 (Non modifiable) */
define( 'CORE', ABSPATH . 'coffee' );

/* 定义系统应用层目录 (Non modifiable) */
define( 'APP', ABSPATH . 'app' );

/* 定义系统配置目录 */
define( 'CONFIG', dirname(__FILE__) . '/' );

/* 定义系统配置目录 */
define( 'THEME', ABSPATH . 'theme' );

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