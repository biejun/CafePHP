<?php

namespace Coffee\Http;

class Request
{
	public $method;

	public $uri = NULL;

	public function __construct()
	{
		$this->method = $this->getMethod();

		$this->uri = $this->getUri();
	}

	public function getUri()
	{
		if(!$this->uri) {
			if (isset($_SERVER['REQUEST_URI'])) {
				return self::safeUrl($_SERVER['REQUEST_URI']);
			}
		}
		return $this->uri;
	}

	/**
	 * 将url中的非法xss去掉时的数组回调过滤函数
	 *
	 * @access private
	 * @param string $string 需要过滤的字符串
	 * @return string
	 */
	public static function removeUrlXss($string)
	{
		$string = str_replace(array('%0d', '%0a'), '', strip_tags($string));
		return preg_replace(array(
			"/\(\s*(\"|')/i",           //函数开头
			"/(\"|')\s*\)/i",           //函数结尾
		), '', $string);
	}
	/**
	* 将url中的非法字符串
	*
	* @param string $url 需要过滤的url
	* @return string
	*/
	public static function safeUrl($url)
	{
		$params = parse_url(str_replace(array("\r", "\n", "\t", ' '), '', $url));
		if (isset($params['scheme'])) {
			if (!in_array($params['scheme'], array('http', 'https'))) {
				return '/';
			}
		}
		$params = array_map('self::removeUrlXss', $params);
		return self::buildUrl($params);
	}

	/**
	* 根据parse_url的结果重新组合url
	*
	* @access public
	* @param array $params 解析后的参数
	* @return string
	*/
	public static function buildUrl($params)
	{
		return (isset($params['scheme']) ? $params['scheme'] . '://' : NULL)
		. (isset($params['user']) ? $params['user'] . (isset($params['pass']) ? ':' . $params['pass'] : NULL) . '@' : NULL)
		. (isset($params['host']) ? $params['host'] : NULL)
		. (isset($params['port']) ? ':' . $params['port'] : NULL)
		. (isset($params['path']) ? $params['path'] : NULL)
		. (isset($params['query']) ? '?' . $params['query'] : NULL)
		. (isset($params['fragment']) ? '#' . $params['fragment'] : NULL);
	}

	public function getMethod()
	{
		return (isset($_SERVER['REQUEST_METHOD'])) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
	}

	public function parseUrl()
	{
		$info = [];
		if ( false !== strpos($this->uri,'?') ){
			$info = parse_url($this->uri);
			return $info;
		}
		return $info;
	}

	public function splitUrl($uri)
	{
		return preg_split('|(?mi-Us)/+|', trim($uri, '/'));
	}

	public function fetchPath($path = '')
	{
		return $this->splitUrl( ((empty($path)) ? $this->getPath() : $path) );
	}

	public function getPath()
	{
		$this->uri = preg_replace('/\.html$/i','',$this->uri);
		$info = $this->parseUrl();

		return (isset($info['path'])) ? $info['path'] : $this->uri;
	}

	public function getQuery()
	{
		$query = [];
		$info = $this->parseUrl();

		if(isset($info['query'])) {
			parse_str($info['query'],$query);
		}
		return $query;
	}

	public function get($name = null, $defaultValue = null)
	{
		if ($name === null) {
			return $_GET;
		} else {
			return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
		}
	}

	public function post($name = null, $defaultValue = null)
	{
		if ($name === null) {
			return $_POST;
		} else {
			return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
		}
	}

	public function isPost()
	{
		return 'POST' === $this->method;
	}

	public function isGet()
	{
		return 'GET' === $this->method;
	}

	public function isAjax()
	{
		return 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'];
	}
}