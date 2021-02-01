<?php namespace Cafe\Http;

/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @version  1.0.0
 * @link     https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

use Closure;
use Cafe\Http\RouteCollection;

class Router
{
    protected $groupStack = [];

    protected $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection;
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
    }

    protected function addRoute(array $methods, $uri, $action)
    {
        return $this->routes->add($this->createRoute($methods, $uri, $action));
    }

    protected function createRoute($methods, $uri, $action)
    {
        return (new Route($methods, $this->prefix($uri), $action));
    }

    protected function prefix($uri)
    {
        return trim(trim($this->getLastGroupPrefix(), '/').'/'.trim($uri, '/'), '/') ?: '/';
    }

    public function getLastGroupPrefix()
    {
        return (empty($this->groupStack))?'':end($this->groupStack);
    }

    public function dispatch($request, $response)
    {
        $this->routes->matchs($request, $response);
    }
}
