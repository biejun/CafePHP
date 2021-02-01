<?php namespace Coffee\Foundation;

/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @version  1.0.0
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

use Coffee\Http\Request;
use Coffee\Http\Response;
use Coffee\Http\Router;

class Container
{
    /* 当前匹配的应用 */
    public $app = '';

    /* 当前匹配应用的路径 */
    public $path = '';

    public function __construct()
    {
        $this->request = new Request;

        $this->response = new Response;

        $this->view = new View;

        $this->action = new Action;

        $this->session = new Session;

        $this->cookie = new Cookie;

        $this->captcha = new Captcha;
    }

    private function appFiles()
    {
        $app = new App($this->response);

        $this->app = $app->matchApp($this->request);

        $this->path = PATH . (empty($this->app)?'':$this->app . '/');

        $this->response->appPath = $this->path;

        return $app->appFiles();
    }

    public function existLock()
    {
        return file_exists(CONFIG.'/install.lock');
    }

    /* 载入应用组件 */
    public function load($component)
    {
        return Component::instance($component);
    }

    public function view($template, $vars = null)
    {
        $this->response->html( $this->view->tpl($template, $vars) );
    }

    public function captchaImage($id) {
        //$content = ;
        $this->response->header('Content-Type', 'image/jpeg')
            ->write($this->captcha->create($id))
            ->send();
    }

    /* 运行应用 */
    public function run()
    {
        $appFiles = $this->appFiles();
        $route = new Router;

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

        $route->dispatch($this->request, $this->response);
    }
}