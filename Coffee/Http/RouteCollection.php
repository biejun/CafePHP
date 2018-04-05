<?php
/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.0.6
 * @link     https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

namespace Coffee\Http;

use Countable;
use Coffee\Support\Arr;
use Coffee\Foundation\Action;

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

    public function matchs($request, $response)
    {

        $route = $this->matchAgainstRoutes($this->get($request->getMethod()), $request);

        $action = new Action;

        $action->on('route:init');

        if( $route ){

            if( is_callable( $route->action ) ) {

                $action->on('route:before');

                call_user_func_array($route->action,$route->args);

                $action->on('route:after');

            }else{

                throw new \Exception("路由第二个参数必须为一个回调函数");
            }

        }else{
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

        return Arr::first($routes,function($value) use ($request, $reqPattern){
            return $value->match($request,$reqPattern);
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
