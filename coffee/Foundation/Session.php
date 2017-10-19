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
namespace Coffee\Foundation;

class Session
{

	private static $state;

	public function __construct()
	{
		if(!isset(self::$state) || self::$state == false)
		{
			self::$state = $this->start();
		}
	}
	/* 启用SESSION会话 */
	public function start()
	{
		if ( $this->isSessionStarted() === false ){
			return session_start();
		}
		return false;
	}

	public function isSessionStarted()
	{
		if ( php_sapi_name() !== 'cli' )
		{
			if ( version_compare(phpversion(), '5.4.0', '>=') )
			{
				return session_status() === PHP_SESSION_ACTIVE ? true : false;
			}
			else
			{
				return session_id() === '' ? false : true;
			}
		}
		return false;
	}

	public function __set($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	public function __get($name)
	{
		if (isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
	}

	public function __unset($name)
	{
		unset($_SESSION[$name]);
	}

	/* 销毁一个会话中的全部数据 */
	public function destroy()
	{
		if(isset(self::$state) && self::$state == true)
		{
			session_unset();
			session_destroy();

			self::$state = false;
		}
	}
}