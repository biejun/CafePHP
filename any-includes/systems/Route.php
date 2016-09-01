<?php
if(!defined('ABSPATH'))exit('Access denied!');

class Route {
	
	public static function get($app,$act='index'){
		if(isset($_SERVER['PATH_INFO'])){
			$routes = self::apps_rewrite();
			$regx = preg_replace('/\.html$/i','',trim($_SERVER['PATH_INFO'],'/'));
			if(!empty($routes)&&is_array($routes)){
				foreach ($routes as $route) {
					if(is_array($route)){
						foreach ($route as $rule => $path) {
							self::rewrite_route($rule,$path,$regx);
						}
					}
				}
			}
		}
		$app = isset($_GET['app'])?$_GET['app']:$app;
		$act = isset($_GET['act'])?$_GET['act']:$act;
		if($app == $act){
			throw new \Exception('app 与 act 命名不能相同，防止重复实例化');
		}else{
			$page =  ANYAPP . $app.'/'. $app. '.php';
			if(is_file($page)){
				require_once($page);
				if(!isset($run)) $run = new $app($app,$act);
				if(stripos($act,'_initialize')!==false)
					$run->http_404();
				if(stripos($act,'post_')!==false)
					$run->verify_post();
				$run->$act();
			}
		}
	}
	private static function apps_rewrite(){
		global $cache;
		$folder = glob( ANYAPP .'*');
		$packages = array();
		$routes = array();
		foreach ($folder as $name) {
			if(is_dir($name)){
				$package = $name.'/package.php';
				if(file_exists($package)){
					$route = include $package;
					$packages[] = $route;
					if(isset($route['route']))
						$routes[] = $route['route'];
				}
			}
		}
		$cache->write('packages',$packages);
		return $routes;
	}
	private static function rewrite_route($rule,$route,$regx){
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
			$var = self::parse_url($url);
			foreach($var as $key=>$val){
				if(strpos($val,'|')){
					list($val,$fun) = explode('|',$val);
					$var[$key] = $fun($val);
				}
			}
			# 解析剩余的URL参数
			$regx = substr_replace($regx,'',0,strlen($matches[0]));
			if($regx) {
				preg_replace_callback('/(\w+)\/([^\/]+)/',
					function($match) use(&$var) {
						$var[strtolower($match[1])] = strip_tags($match[2]);
					}, $regx);
			}
			# 解析路由自动传入参数
			if(is_array($route) && isset($route[1])){
				if(is_array($route[1])){
					$params = $route[1];
				}else{
					parse_str($route[1],$params);
				}
				$var = array_merge($var,$params);
			}
			$_GET = array_merge($var,$_GET);
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