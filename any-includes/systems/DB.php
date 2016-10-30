<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *	@package MYSQL DATABASE
 */
class DB{
	private static $charset = 'utf8';
	private static $collate = 'utf8_general_ci';
	public static $db_handle;
	public static function factory($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_lib,$db_create=false){
		if('mysqli'== $db_lib){
			require_once(ANYSYSTEM . 'Db/MySqlIm.php');
			return isset(self::$db_handle) ? self::$db_handle :
			(self::$db_handle = new MySqlIm($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_create,self::$charset,self::$collate));
		}else{
			require_once(ANYSYSTEM . 'Db/MySql.php');
			return isset(self::$db_handle) ? self::$db_handle :
			(self::$db_handle = new MySql($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_create,self::$charset,self::$collate));
		}
	}
}