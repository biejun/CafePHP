<?php namespace Coffee\Http;
/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @version  1.0.0
 * @link     https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

class Route
{
    public $methods;

    public $uri;

    public $action;

    public $args = [];

    public $container;

    public function __construct($methods, $uri, $action)
    {

        $this->methods = (array) $methods;

        $this->uri = $uri;

        $this->action = $action;

        if (in_array('GET', $this->methods) && !in_array('HEAD', $this->methods)) {
            $this->methods[] = 'HEAD';
        }
    }

    public function methods()
    {
        return $this->methods;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function match($request, $reqPattern)
    {

        $uriPattern = $request->fetchPath($this->uri);

        // 如果请求地址长度大于路由配置地址直接退出
        if(count($reqPattern) > count($uriPattern)) return false;

        return $this->parseArg($reqPattern,$uriPattern);
    }

    private function parseArg($reqPattern, $uriPattern)
    {
        // 匹配链接中带:的参数
        preg_match_all('|(?mi-Us):\\w+\\??|', $this->uri, $matches);

        foreach ( $uriPattern as $k => $v ) {

            // 如果路由配置中存在参数
            if ( isset($matches[0]) && in_array( $v, $matches[0] ) ) {

                $paramName = trim($v, ':');

                // 懒惰匹配
                if (substr($v, -1) == '?') {

                    $paramName = rtrim($paramName, '?');

                    if (array_key_exists($k, $reqPattern)) {

                        $this->args[$paramName] = $reqPattern[$k];
                        continue;
                    }else{
                        $this->args[$paramName] = NULL;
                        return true;
                    }

                }else{
                    // 全局匹配
                    if (array_key_exists($k, $reqPattern)) {
                        $this->args[$paramName] = $reqPattern[$k];
                        continue;
                    }else{
                        return false;
                    }
                }
            }
            // 无参数请求
            $uriValue = array_key_exists( $k, $reqPattern ) ? $reqPattern[$k] : NULL;

            if ($v != $uriValue) return false;
        }
        return true;
    }

    public function __set($key,$value)
    {
        if(!isset($this->container[$key])){
            $this->container[$key] = $value;
        }
    }

    public function __get($name)
    {
        if(isset($this->container[$name])){
            return $this->container[$name];
        }
        return false;
    }
}