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

use Coffee\Http\Request;
use Coffee\Http\Response;
use Coffee\Http\Router;
use Coffee\Foundation\Action;
use Coffee\Foundation\View;

class App
{
	/* 定义系统默认语言 */
	public $lang = 'zh_CN';

	public $version = '0.0.6';

	public $request = NULL;

	public $response = NULL;

	public $view = NULL;

	public $action = NULL;

	public function __construct()
	{
		$this->setCharset();

		$this->setTimezone();

		$this->setEnvironment();
	}

	public function run()
	{
		$this->request = new Request;

		$this->response = new Response;

		$this->view = new View;

		$this->action = new Action;

		$this->sendHeaders();

		$this->process($this->request, $this->response);
	}

	private function setCharset()
	{
		mb_internal_encoding(CHARSET);

		header("Content-type: text/plain; charset=" . CHARSET);
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
	public function process(Request $request, Response $response)
	{
		$appFiles = $this->matchApp($request);

		$route = new Router($request,$response);

		// 加载路由
		array_walk($appFiles['route'], function($file,$deep,$route)
		{
			if(file_exists($file))
			{
				include $file;
			}
		},$route);

		// 加载动作
		array_walk($appFiles['action'], function($file,$deep,$action)
		{
			if(file_exists($file))
			{
				include $file;
			}
		},$this->action);

		$route->dispatch();
	}

	public function matchApp(Request $request)
	{

		$paths = $request->fetchPath();

		$route = $action = [];

		$route[] = APP . '/route.php';

		$action[] = APP. '/action.php';

		$app = array_pop($paths);

		if(!empty($app))
		{
			$route[] = APP . '/' . strtolower($app) . '/route.php';
			
			$action[] = APP . '/' . strtolower($app) . '/action.php';
		}

		return ['route' => $route, 'action' => $action];
	}
}