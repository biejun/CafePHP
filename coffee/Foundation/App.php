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

/**
 * 系统应用层核心代码
 *
 * @package Coffee\Foundation\App
 * @since 0.0.5 包含应用初始化，定义了全局变量$route和$action
 */

namespace Coffee\Foundation;

class App
{
	/* 定义系统默认字符编码集 */
	public $charset = 'utf-8';

	/* 定义系统默认语言 */
	public $lang = 'zh_CN';

	public $version = '0.0.6';

	public function __construct()
	{
		$this->setCharset();

		$this->setTimezone();

		$this->setEnvironment();
	}

	public function run()
	{
		$this->sendHeaders();

		$this->process();
	}

	private function setCharset()
	{
		mb_internal_encoding($this->charset);

		header("Content-type: text/plain; charset={$this->charset}");
	}

	private function setEnvironment()
	{
		$this->environment( !isset($_SERVER['CI_ENV'])?:$_SERVER['CI_ENV'] );
	}

	private function environment($env)
	{
		$env = $env || IS_DEVELOPMENT ? 'development' : 'production';

		switch ($env)
		{
			case 'development':
				error_reporting(-1);
				ini_set('display_errors', 1);
			break;
			case 'testing':
			case 'production':
				ini_set('display_errors', 0);
				if (version_compare(PHP_VERSION, '5.3', '>='))
				{
					error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
				}
				else
				{
					error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
				}
			break;
			default:
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo('应用环境没有设置正确。');
				exit(1);
		}
	}

	private function setTimezone()
	{
		date_default_timezone_set(TIMEZONE);
	}

	/* 向浏览器发送头部信息 */
	private function sendHeaders()
	{
		header("X-Powered-By: Coffee/{$this->version}");
	}

	/* 处理一个请求 */
	public function process()
	{

	}
}