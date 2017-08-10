<?php
/**
 * 系统应用层
 *
 * @package Coffee\Foundation\App
 * @since 0.0.5 包含应用初始化，定义了全局变量$route和$action
 */
namespace Coffee\Foundation;

use Coffee\Http\Request;
use Coffee\Http\Response;
use Coffee\Http\Router;
use Coffee\Foundation\Action;

class App
{
	const VERSION = '0.0.5/15.06.14';

	public $root;

	public $request;

	public $response;

	public function __construct()
	{

		$this->request = new Request;

		$this->response = new Response;
	}

	// 启动应用
	public function start()
	{

		$this->init();

		$this->sendHeaders();

		if($appFiles = $this->getAppFiles()){

			$route = new Router($this->request,$this->response);

			// 加载路由
			array_walk($appFiles['route'], function($file,$deep,$route)
			{
				if(file_exists($file)){

					include $file;
				}
			},$route);

			// 加载动作
			array_walk($appFiles['action'], function($file,$deep,$action)
			{
				if(file_exists($file)){

					include $file;
				}
			},new Action);

			$route->dispatch();
		}
	}

	private function sendHeaders()
	{

		if (!headers_sent()) {

			header("Content-type: text/html; charset=UTF-8");

			header("X-Powered-By: Coffee/".self::VERSION);
		}
	}

	private function init()
	{

		ini_set('display_errors', conf('system','debug') ? 'On' : 'Off');

		error_reporting(conf('system','debug') ? E_ALL ^ E_NOTICE : 0);

		mb_internal_encoding(conf('system','charset'));

		date_default_timezone_set(conf('system','timezone'));

		session_start();
	}

	// 获取应用路由配置
	private function getAppFiles()
	{
		$appPath = $this->getAppPath();

		$reqPath = $this->request->fetchPath();

		$route = $action = [];

		$route[] = $appPath . 'route.php';

		$action[] = $appPath . 'action.php';

		if(!empty($reqPath[0])){

			$reqPath = $appPath . ucfirst($reqPath[0]) . DIRECTORY_SEPARATOR;

			$route[] = $reqPath . 'route.php';

			$action[] = $reqPath . 'action.php';
		}

		return ['route'=>$route,'action'=>$action];
	}

	// 设置系统根目录
	public function setRoot($root)
	{
		$this->root = $root . DIRECTORY_SEPARATOR;

		$this->response->view->root = $root . DIRECTORY_SEPARATOR;

		return $this;
	}

	// 获取应用路径
	public function getAppPath()
	{
		return $this->root .'app'. DIRECTORY_SEPARATOR;
	}
}