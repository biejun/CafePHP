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

class App
{
	/* 定义系统默认语言 */
	public $lang = 'zh-CN';

	public $version = '0.0.6/15.06.14';

	public $request = null;

	public $response = null;

	public $view = null;

	public $action = null;

	public $session = null;

	public $cookie = null;

	/* 初始化设置 */
	public function __construct()
	{
		$this->setCharset();

		$this->setTimezone();

		$this->setEnvironment();

		$this->exceptionAndErrorHandler();
	}

	/* 运行应用 */
	public function run()
	{
		$this->request = new Request;

		$this->response = new Response;

		$this->view = new View;

		$this->view->lang = $this->lang;

		$this->action = new Action;

		$this->session = new Session;

		$this->cookie = new Cookie;

		$this->sendHeaders();

		$this->process();
	}
	/* 设置系统字符编码集 */
	private function setCharset()
	{
		mb_internal_encoding(CHARSET);

		header("Content-type: text/plain; charset=" . CHARSET);
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

	/* 设置时区 */
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
		$appFiles = $this->matchApp();

		$route = new Router($this->request,$this->response);

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

	/* 匹配应用 */
	public function matchApp()
	{

		$paths = $this->request->fetchPath();

		$route = $action = [];

		$route[] = APP . '/route.php';

		$action[] = APP. '/action.php';

		$app = array_shift($paths);

		if(!empty($app))
		{
			$route[] = APP . '/' . strtolower($app) . '/route.php';

			$action[] = APP . '/' . strtolower($app) . '/action.php';
		}

		return ['route' => $route, 'action' => $action];
	}

	/* 载入应用组件 */
	public function import($component)
	{
		return Component::instance($component);
	}

	/* 渲染一个页面 */
	public function render($tpl,$vars = null,$status = 200)
	{
		$this->response->status($status)
			->header('Content-Type', 'text/html; charset='.CHARSET)
			->write($this->view->tpl($tpl,$vars))
			->send();
	}

	public function checkSystemInit()
	{
		# 判断系统是否已上锁，未上锁就进行初始化配置
		if(!file_exists(CONFIG . '/install.lock'))
		{
			$php_sapi = PHP_SAPI;

			if($php_sapi === 'apache2handler')
			{
				urlRewriteByApache(PATH);
			}
			else if($php_sapi === 'fpm-fcgi' || $php_sapi === 'cgi-fcgi')
			{
				urlRewriteByNginx(PATH);
			}

			if(false === strpos($this->request->getPath(),'install'))
			{
				$this->response->redirect(PATH . 'install?step=1');
			}
		}
	}

	/* 系统异常和错误处理 */
	public function exceptionAndErrorHandler()
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
}