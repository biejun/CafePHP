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

use Countable;
use Cafe\Support\Arr;

class RouteCollection implements Countable
{
    protected $routes = [];

    protected $allRoutes = [];

    public function add(Route $route)
    {
        $this->addToCollections($route);

        return $route;
    }

    protected function addToCollections($route)
    {
        $uri = $route->uri();
        foreach ($route->methods() as $method) {
            $this->routes[$method][$uri] = $route;
        }
        $this->allRoutes[$method.$uri] = $route;
    }

    public function matchs($action, $request, $response)
    {
        $route = $this->matchAgainstRoutes($this->get($request->getMethod()), $request);
        $action->on('route:init');
        if ($route) {
            if (is_callable($route->action)) {
				$action->once('route:view');
                $action->on('route:before');
                
                array_walk($route->actions, function($args, $key, $action) {
                    call_user_func_array(array($action, 'on'), $args);
                }, $action);
                
                $request->setParams($route->args);
                call_user_func_array($route->action, [$request, $response]);
                $action->on('route:after');
            } else {
                throw new \Exception("路由的第二个参数必须是一个回调函数");
            }
        } else {
            $action->on('route:failed');
        }
    }

    # 取出某种请求方式下的路由配置
    public function get($method = null)
    {
        return is_null($method) ? $this->getRoutes() : Arr::get($this->routes, $method, []);
    }

    protected function matchAgainstRoutes(array $routes, $request)
    {
        $reqPattern = $request->fetchPath();

        return Arr::first($routes, function ($value) use ($request, $reqPattern) {
            return $value->match($request, $reqPattern);
        });
    }

    public function getRoutes()
    {
        return array_values($this->allRoutes);
    }

    public function count()
    {
        return count($this->getRoutes());
    }
}
