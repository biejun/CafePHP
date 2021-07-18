<?php namespace Cafe\Http;
/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @link     https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2021 Jun Bie
 * @license This content is released under the MIT License.
 */

use Closure;
use Cafe\Http\RouteCollection;

class Router
{
    protected $groupStack = [];

    protected $routes;
    
    protected $actions = [];
	
	public $container;

    public function __construct()
    {
        $this->routes = new RouteCollection;
    }
    
    public function on()
    {
        $this->actions[] = func_get_args();
        return $this;
    }

    public function get($uri, $action)
    {
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute(['POST'], $uri, $action);
    }

    public function put($uri, $action)
    {
        return $this->addRoute(['PUT'], $uri, $action);
    }

    public function patch($uri, $action)
    {
        return $this->addRoute(['PATCH'], $uri, $action);
    }

    public function delete($uri, $action)
    {
        return $this->addRoute(['DELETE'], $uri, $action);
    }

    public function any($uri, $action)
    {
        $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];

        return $this->addRoute($verbs, $uri, $action);
    }

    public function group($prefix, $routes)
    {
        if (!empty($this->groupStack)) {
            $prefix = end($this->groupStack) . $prefix;
        }
        $this->groupStack[] = $prefix;

        if ($routes instanceof Closure) {
            $routes($this);
        }

        array_pop($this->groupStack);
        
        $this->emptyActions();
    }
    
    protected function emptyActions()
    {
        if(count($this->actions) && count($this->groupStack) === 0) {
            $this->actions = [];
        }
    }

    protected function addRoute(array $methods, $uri, $action)
    {
        $route = $this->createRoute($methods, $uri, $action);
        $this->emptyActions();
        return $this->routes->add($route);
    }

    protected function createRoute($methods, $uri, $action)
    {
        return (new Route($methods, $this->prefix($uri), $action, $this->actions));
    }

    protected function prefix($uri)
    {
        return trim(trim($this->getLastGroupPrefix(), '/').'/'.trim($uri, '/'), '/') ?: '/';
    }

    public function getLastGroupPrefix()
    {
        return (empty($this->groupStack))?'':end($this->groupStack);
    }

    public function dispatch($action, $request, $response)
    {
        $this->routes->matchs($action, $request, $response);
    }
	
	public function __set($key, $value)
	{
	    if (!isset($this->container[$key])) {
	        $this->container[$key] = $value;
	    }
	}
	
	public function __get($name)
	{
	    if (isset($this->container[$name])) {
	        return $this->container[$name];
	    }
	    return false;
	}
}
