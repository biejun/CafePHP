<?php
if( !defined('IS_ANY') ) exit('Access denied!');

class Router{

	private $methods;

	private $uri;

	private $action;

	private $pattern = NULL;

	private $args;

	private $ui = NULL;

	private $params = [];

	public function __construct( array $methods, $uri, $action ){

		$this->methods = $methods;

		$this->uri = $uri;

		$this->action = $action;

		$this->ui = new UI( Core::$parameter , $uri );

	}
	public function theme( $theme ){

		$this->ui->theme = $theme;
	}
	public function parseUrl( $uri ){

		return preg_split('|(?mi-Us)/+|', trim($uri, '/'));
	}
	public function match( $uri,$method ){

		if( !$this->allowMethod( $method ) ) return false;

		// 解析路由器中配置的链接

		if( NULL === $this->pattern ) $this->pattern = $this->parseUrl( $this->uri );

		// 解析当前请求的链接

		$requestUri = $this->parseUrl( $uri );

		// 如果请求地址长度大于路由配置地址直接退出

		if( count( $requestUri ) > count( $this->pattern ) ) return false;

		// 匹配链接中带:的参数

		preg_match_all('|(?mi-Us):\\w+\\??|', $this->uri, $matches);

		// 参数个数

		$this->args = (isset($matches[0])) ? count($matches[0]) : 0;

		foreach ( $this->pattern as $key => $value ) {

				// 如果路由配置中存在参数

				if ( in_array( $value, $matches[0] ) ) {

					$param_name = trim($value, ':');

					// 懒惰匹配

					if (substr($value, -1) == '?') {

						if (array_key_exists($key, $requestUri)) {

							$this->params[$param_name] = $requestUri[$key];
							continue;
						}else{
							$this->params[$param_name] = NULL;
							return true;
						}

					}else{

						// 全局匹配

						if (array_key_exists($key, $requestUri)) {

							$this->params[$param_name] = $requestUri[$key];
							continue;
						}else{
							return false;
						}
					}
				}

				// 无参数请求

				$uriValue = array_key_exists( $key, $requestUri ) ? $requestUri[$key] : NULL;

				if ( $value != $uriValue ) return false;
		}

		return true;

	}
	// 请求方法检查
	private function allowMethod( $method ){

		return in_array($method, $this->methods);

	}
	public function __get( $name ){

		return $this->$name;
	}
}