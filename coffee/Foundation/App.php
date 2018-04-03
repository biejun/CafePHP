<?php namespace Coffee\Foundation;
/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.1.0
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

class App
{
	public $version = 'cafe/1.0.0';

	public $routes = [];

	public $actions = [];

	public function __construct($response)
	{
		$this->setCharset();

		$this->setTimezone();

		$this->exceptionAndErrorHandler($response);

		$this->setEnvironment();

		$this->sendHeaders();
	}

	/* 设置系统字符编码集 */
	private function setCharset()
	{
		mb_internal_encoding(CHARSET);
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
				throw new \Exception("应用环境没有设置正确", 1);
				exit(1);
		}
	}

	/* 向浏览器发送头部信息 */
	private function sendHeaders()
	{
		header("X-Powered-By: {$this->version}");
	}

	/* 系统异常和错误处理 */
	public function exceptionAndErrorHandler($response)
	{
		set_error_handler(function($errNo, $errStr, $errFile, $errLine) use ($response) {
			$error = [];
			$error[] = 'Message '.$errStr;
			$error[] = 'File '.$errFile;
			$error[] = 'Line '.$errLine;
			$response->text(implode("\n",$error));
		});

		set_exception_handler(function($e)  use ($response) {
			$exception = [];
			$exception[] = 'Service exception.';
			$exception[] = 'Message '.$e->getMessage();
			$exception[] = 'File '.$e->getFile();
			$exception[] = 'Line '.$e->getLine();
			$exception[] = 'Trace at '.$e->getTraceAsString();
			$response->text(implode("\n",$exception));
		});
	}

	/* 匹配应用 */
	public function matchApp($request)
	{
		$this->routes[] = APP . '/route.php';
		$this->actions[] = APP . '/action.php';

		$paths = $request->fetchPath();
		$app = array_shift($paths);

		if(!empty($app)) {
			$this->routes[] = APP . '/' .strtolower($app). '/route.php';
			$this->actions[] = APP . '/' .strtolower($app).'/action.php';
		}

		return $app;
	}

	public function appFiles()
	{
		return ['routes' => $this->routes , 'actions' => $this->actions];
	}
}