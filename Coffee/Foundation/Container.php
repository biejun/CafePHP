<?php namespace Coffee\Foundation;

/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.0.6
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

use Coffee\Http\Request;
use Coffee\Http\Response;
use Coffee\Http\Router;

class Container
{
    public function __construct()
    {
        $this->request = new Request;

        $this->response = new Response;

        $this->view = new View;

        $this->action = new Action;

        $this->session = new Session;

        $this->cookie = new Cookie;
    }

    public function init()
    {
        $app = new App;

        $this->response->setCharset(CHARSET);

        $this->response->setViewRender($this->view);

        return $app->matchApp($this->request->fetchPath());
    }

    public function existLock()
    {
        return file_exists( CONFIG . '/install.lock' );
    }

    /* 载入应用组件 */
    public function load($component)
    {
        return Component::instance($component);
    }

    public function run()
    {
        $appFiles = $this->init();

        $route = new Router($this->request,$this->response);

        // 加载路由
        array_walk($appFiles['routes'], function($file,$deep,$route)
        {
            if(file_exists($file))
            {
                include $file;
            }
        },$route);

        // 加载动作
        array_walk($appFiles['actions'], function($file,$deep,$action)
        {
            if(file_exists($file))
            {
                include $file;
            }
        },$this->action);

        $route->dispatch();
    }
}