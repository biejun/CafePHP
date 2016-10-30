<?php
if(!defined('ABSPATH'))exit('Access denied!');

class Route {

	private static $_var = array();

	private static $_instance = null;

	public static function get($app,$act='index'){
		# 根据PATHINFO获取应用和页面，部分服务器可能没这个变量，暂时支持APACHE
		if( isset($_SERVER['PATH_INFO'])) {
			$routes = self::apps_rewrite();
			$regx = preg_replace('/\.html$/i','',trim($_SERVER['PATH_INFO'],'/'));
			if(!empty($routes)&&is_array($routes)){
				foreach ($routes as $route) {
					array_walk($route,'self::rewrite_route',$regx);
				}
			}
		}
		if(empty(self::$_var)){
			self::$_var['app'] = isset($_GET['app'])?$_GET['app']:$app;
			self::$_var['act'] = isset($_GET['act'])?$_GET['act']:$act;
		}

		$app = strtolower(self::$_var['app']);
		$act = strtolower(self::$_var['act']);

		$app_file = ANYAPP . $app .'/index.php';
		if(is_file($app_file) && $app!=$act) {
			require_once($app_file);
			$ui_theme = ($app == 'admin'||stripos($act,'admin_')!==false) ? 'admin' : widget()->get_theme();
			if (null === self::$_instance) {
				if(class_exists($app)) self::$_instance = new $app($ui_theme);
			}
			if($app == 'admin'&&!in_array($act, array('login','post_login_access'))||stripos($act,'admin_')!==false)
				if(!widget('admin:user')->is_admin()) self::$_instance->http_404();
			if(stripos($act,'_initialize')!==false) self::$_instance->http_404();
			if(stripos($act,'post_')!==false){
				if($_SERVER['REQUEST_METHOD']!=='POST' || empty($_SERVER['HTTP_REFERER']))
					self::$_instance->http_404(); # 检查请求方式或来路，非法则显示404
			}
			self::$_instance->$act();
		}
	}
	private static function apps_rewrite(){
		$folder = glob( ANYAPP .'*',GLOB_ONLYDIR);
		$packages = array();
		$routes = array();
		foreach ($folder as $name) {
			$package = $name.'/package.php';
			if(file_exists($package)){
				$route = include $package;
				$packages[] = $route;
				if(isset($route['route']))
					$routes[] = $route['route'];
			}
		}
		$GLOBALS['cache']->write('packages',$packages);
		return $routes;
	}
	private static function rewrite_route($route,$rule,$regx){
		if(is_numeric($rule)) $rule = array_shift($route);
		if(0 === strpos($rule,'/') && preg_match($rule,$regx,$matches)){
			self::parse_regex($matches,$route,$regx);
		}
	}
	private static function parse_regex($matches,$route,$regx) {
		$url = is_array($route)?$route[0]:$route;
		$url = preg_replace_callback('/:(\d+)/',
					function($match) use($matches) {
						return $matches[$match[1]];
					}, $url);
		if(0 === strpos($url,'/') || 0 === strpos($url,'http')){
			header("Location: $url", true, (is_array($route) && isset($route[1]))?$route[1]:301);
			exit;
		}else{
			# 解析路由地址
			self::$_var = self::parse_url($url);
		}
	}
	private static function parse_url($url) {
		$var = array();
		if(false !== strpos($url,'?')) { # [应用/页面] 包含?后面的参数与值
			$info = parse_url($url);
			$path = explode('/',$info['path']);
			parse_str($info['query'],$var);
		}elseif(strpos($url,'/')){ # [应用/页面] 
			$path = explode('/',$url);
		}else{
			parse_str($url,$var); # 解析参数
		}
		if(isset($path)) {
			$var['act'] = array_pop($path);
			$var['app'] = array_pop($path);
		}
		return $var;
	}
}