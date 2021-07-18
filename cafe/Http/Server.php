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

use Cafe\Foundation\Action;

class Server
{
    protected $app;
    
    protected $model;
    
    protected $view;
    
    public function __construct($app)
    {
        $this->app = $app;
        // 初始化这些公用类， 你可以在路由中直接使用这些
        $this->request = new Request;
        $this->response = new Response;
        
        $this->setCharset();
        $this->setTimezone();
        $this->setEnvironment();
        $this->sendHeaders();
        
        // 初始化数据库连接
        $app->make('db');
        
        $this->view  = $app->make('view');
    }
    /* 渲染视图 */
    public function render($template, $vars = [])
    {
        $this->response->html($this->view->render($template, $vars));
    }
    
    /* 设置系统字符编码集 */
    private function setCharset()
    {
        mb_internal_encoding(CHARSET);
    }
    
    /* 设置时区 */
    private function setTimezone()
    {
        date_default_timezone_set($this->app->getConfig('timezone', 'UTC'));
    }
    
    /* 设置系统环境变量 */
    private function setEnvironment()
    {
        $this->environment(!isset($_SERVER['CI_ENV'])?:$_SERVER['CI_ENV']);
    }
    
    private function environment($env)
    {
        $env = $env || IS_DEVELOPMENT ? 'development' : 'production';
    
        switch ($env) {
            case 'production':
                error_reporting(-1);
                ini_set('display_errors', 0);
            break;
            case 'development':
                ini_set('display_errors', 1);
                if (version_compare(PHP_VERSION, '5.3', '>=')) {
                    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                } else {
                    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
                }
            break;
            default:
                header('HTTP/1.1 503 Service Unavailable.', true, 503);
                throw new \Exception("应用环境没有设置正确", 1);
                exit(1);
        }
    }
    
    /* 运行应用 */
    public function run()
    {
        $appFiles = $this->app->matchApp($this->request->fetchPath());
        $route = new Router;
        $action = new Action;
        
        // 加载路由
        array_walk($appFiles['routes'], function ($file, $deep, $route) {
            if (file_exists($file)) {
                include $file;
            }
        }, $route);
        
        // 加载动作
        array_walk($appFiles['actions'], function ($file, $deep, $action) {
            if (file_exists($file)) {
                include $file;
            }
        }, $action);

        $route->dispatch($action, $this->request, $this->response);
    }
    
    /* 向浏览器发送头部信息 */
    private function sendHeaders()
    {
        header("X-Powered-By: {$this->app->version()}");
    }
    
    public function getAppName() {
        return $this->app->appName;
    }
    
    public function getAppPath() {
        return $this->app->appPath;
    }
}