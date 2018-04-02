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

class App
{
	public $version = '0.0.6/15.06.14';

	public function __construct()
	{
		$this->setCharset();

		$this->setTimezone();

		$this->setEnvironment();

		$this->exceptionAndErrorHandler();

		$this->sendHeaders();
	}

	/* 设置系统字符编码集 */
	private function setCharset()
	{
		mb_internal_encoding(CHARSET);

		header("Content-type: text/plain; charset=" . CHARSET);
	}

	/* 设置时区 */
	private function setTimezone()
	{
		date_default_timezone_set(TIMEZONE);
	}

	/* 设置系统环境变量 */
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

	/* 向浏览器发送头部信息 */
	private function sendHeaders()
	{
		header("X-Powered-By: Coffee/{$this->version}");
	}

	/* 系统异常和错误处理 */
	private function exceptionAndErrorHandler()
	{
		set_error_handler(function($errNo, $errStr, $errFile, $errLine){
			$error = [];
			$error['message'] = $errStr;
			$error['file'] = $errFile;
			$error['line'] = $errLine;
			print_r($error);
		});

		set_exception_handler(function($e){
			$exception = [];
			$exception['message'] = $e->getMessage();
			$exception['file'] = $e->getFile();
			$exception['line'] = $e->getLine();
			$exception['trace'] = $e->getTraceAsString();
			print_r($exception);
		});
	}

	/* 匹配应用 */
	public function matchApp($paths = [])
	{
		$routes = $actions = [];
		$routes[] = APP . '/route.php';
		$actions[] = APP. '/action.php';
		$app = array_shift($paths);
		if(!empty($app)) {
			$routes[] = APP . '/' . strtolower($app) . '/route.php';
			$actions[] = APP . '/' . strtolower($app) . '/action.php';
		}
		return ['routes' => $routes, 'actions' => $actions];
	}
}