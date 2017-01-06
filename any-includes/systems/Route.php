<?php
if( !defined('IS_ANY') ) exit('Access denied!');

abstract class Route{

	protected static $allRoutes = array();

	public static function get( $uri, $action ){

		return self::addRoute(['GET', 'HEAD'], $uri, $action);
	}

	public static function post( $uri, $action ){

		return self::addRoute(['POST'], $uri, $action);
	}

	public static function put( $uri, $action ){

		return self::addRoute(['PUT'], $uri, $action);
	}

	public static function patch( $uri, $action ){

		return self::addRoute(['PATCH'], $uri, $action);
	}

	public static function delete( $uri, $action ){

		return self::addRoute(['DELETE'], $uri, $action);
	}

	public static function any( $uri, $action ){

		$verbs = array('GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE');

		return self::addRoute($verbs, $uri, $action);
	}

	protected static function addRoute( array $methods, $uri, $action ){

		if ( in_array( 'GET',$methods ) && !in_array( 'HEAD',$methods ) ) $methods[] = 'HEAD';

		$router = new Router( $methods, $uri, $action );

		self::$allRoutes[] = $router;

		return $router;

	}

	public static function dispatch( $apps ){

		$uri = str_replace( array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e') ,'',$_SERVER['REQUEST_URI'] );

		if(PATH !== '/' ) $uri = str_replace( PATH , '/' , $uri );

		$uri = preg_replace('/\.html$/i','',$uri);

		$var = [];

		if ( false !== strpos($uri,'?') ){

			$info = parse_url($uri);

			if( isset( $info['path'] ) ) $uri = $info['path'];

			if( isset( $info['query'] ) ) parse_str($info['query'],$var);

		}

		$method = $_SERVER['REQUEST_METHOD'];

		foreach ($apps as $app) {
			
			$file = ANYAPP. $app . DIRECTORY_SEPARATOR .'route.php';

			if ( is_file( $file ) ) {

				include $file;
			}
		}

		$route = self::_matchRoute( $uri, $method );

		if( $route ){

			if( is_callable( $route->action ) ) {

				if(!empty($var)) $route->ui->props = $var;

				call_user_func_array( $route->action,array( $route->ui , $route->params) );

			}else{

				throw new Exception("路由第二个参数必须为一个回调函数");
			}

		}else{
			
			header('HTTP/1.1 404 Not Found');
			
			header("status: 404 Not Found");
			
			exit;
		}
	}

	// 路由匹配
	private static function _matchRoute( $uri, $method ){

		foreach(self::$allRoutes as $route){

			if($route->match($uri, $method)){

				return $route;
			}
		}

		return false;
	}

}