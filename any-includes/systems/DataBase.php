<?php
if( !defined('IS_ANY') ) exit('Access denied!');

class DataBase{

	public static function factory( $config , $db_create = false ){

		$charset = 'utf8';

		$collate = 'utf8_general_ci';

		$db_driver = $config['driver'];

		$db_host = $config['host'];

		$db_name = $config['name'];

		$db_user = $config['user'];

		$db_password = $config['password'];

		$db_prefix = $config['prefix'];

		$db_path = ANYINC . 'systems';

		if( $db_driver == 'mysqli' ){
			
			require_once  $db_path . '/Db/MySqlIm.php';
			
			return new MySqlIm($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_create,$charset,$collate);
		}else{

			require_once  $db_path . '/Db/MySql.php';

			return new MySql($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_create,$charset,$collate);
		}
	}
}
