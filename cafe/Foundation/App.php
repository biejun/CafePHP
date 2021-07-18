<?php namespace Cafe\Foundation;

/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @link     https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2021 Jun Bie
 * @license This content is released under the MIT License.
 */

use ArrayAccess;
use Closure;
use Cafe\Support\Arr;
use Cafe\Foundation\View;
use Cafe\Foundation\Captcha;
use Cafe\Cache\Cache;
use Illuminate\Database\Capsule\Manager as DatabaseManager;

class App implements ArrayAccess
{
    const VERSION = 'cafe/1.1.0';
    protected static $instance;
    /* 依赖库函数绑定 */
    protected $bindings = [];
    /* 系统物理路径 */
    protected $basePath;
    /* 当前匹配的应用名称 */
    protected $appName = '';
    /* 当前匹配应用的路径 */
    protected $appPath = '';
    /* 系统配置 */
    protected $config = [];
    
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }
        
        $this->config = $this->loadConfig();
        $this->registerBaseBindings();
    }
    
    protected function registerBaseBindings()
    {
        static::setInstance($this);
        
        $this->bind('db.config', function ($app) {
            $file = $app->configPath('config.db.php');
            if (!file_exists($file)) {
                throw new \Exception("数据库配置文件不存在！");
            }
            return include($file);
        });
        $this->bind('db', function ($app) {
            $conf = $app->make('db.config');
            $db = new DatabaseManager();
            $db->addConnection($conf);
            if($db->getConnection()) {
               $db->setAsGlobal();
            } 
        });
        $this->bind('view', View::class);
        $this->bind('session', function($app) {
            return new Session;
        });
        $this->bind('cookie', function($app) {
            return new Cookie;
        });
        $this->bind('captcha', function($app) {
            return new Captcha;
        });
        /* 数据缓存 */
        $this->bind('data', function ($app) {
            return Cache::init($app->storagePath('cache'));
        });
        /* 日志缓存 */
        $this->bind('log', function ($app) {
            return Cache::init(['folder'=> $app->storagePath('logs')]);
        });
    }
    
    // 设置系统根路径
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        return $this;
    }
    // 应用目录
    public function appPath($path = '')
    {
        return $this->pathJoin('app', $path);
    }
    // 配置目录
    public function configPath($path = '')
    {
        return $this->pathJoin('config', $path);
    }
    // 公共目录
    public function publicPath($path = '')
    {
        return $this->pathJoin('public', $path);
    }
    // 存储目录
    public function storagePath($path = '')
    {
        return $this->pathJoin('storage', $path);
    }
    
    // 公共目录
    public function viewPath($path = '')
    {
        return $this->pathJoin('view', $path);
    }
    
    /* 将多个参数组合成一个路径 */
    public function pathJoin()
    {
        $path = array();
        $args = func_get_args();
        $spea = DIRECTORY_SEPARATOR;
        if (count($args) > 0) {
            foreach ($args as $key => $value) {
                if ($value) {
                    $path[] = $value;
                }
            }
        }
        return $this->basePath.$spea.join($spea, $path);
    }
    
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
    
        return static::$instance;
    }
    
    public static function setInstance($container = null)
    {
        return static::$instance = $container;
    }
    
    public function version()
    {
        return static::VERSION;
    }
    // 绑定一个类
    public function bind($abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        $this->bindings[$abstract] = $concrete;
    }
    // 获取绑定
    public function getBind($abstract)
    {
        return $this->bindings[$abstract];
    }
    // 调用给定名称的绑定
    public function make($abstract)
    {
        $binding = $this->getBind($abstract);
        if (!isset($binding)) {
            return null;
        }
       
        if (is_string($binding)) {
            return new $binding($this);
        } elseif ($binding instanceof Closure) {
            return $binding($this);
        }
    }
    // 检查是否已安装
    public function existLock()
    {
        return file_exists($this->configPath('install.lock'));
    }
    // 加载配置文件
    private function loadConfig()
    {
        if (file_exists($path = $this->configPath('config.site.php'))) {
            return include $path;
        }
        return [];
    }
    // 获取配置
    public function getConfig($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }
    // 匹配应用
    public function matchApp($paths = [])
    {
        $routes = [];
        $actions = [];
        
        $routePath = $this->appPath('routes');
        if (count($paths) > 1) {
            $firstPath = array_shift($paths);
            if (!empty($firstPath)) {
                $this->appName = strtolower($firstPath);
                $routes[] = $routePath .'/'.$this->appName .'/route.php';
                $actions[] = $routePath .'/'.$this->appName .'/action.php';
            }
        }
        $routes[] = $routePath .'/route.php';
        $actions[] = $routePath .'/action.php';
        
        $this->appPath = PATH .(!$this->appName?:$this->appName . '/');
        
        return [
            'routes' => $routes,
            'actions' => array_reverse($actions)
        ];
    }
    /**
     * Determine if the given abstract type has been bound.
     *
     * @param  string  $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        return isset($this->bindings[$abstract]);
    }
    /**
     * Determine if a given offset exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->bound($key);
    }
    
    /**
     * Get the value at a given offset.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->make($key);
    }
    /**
     * Set the value at a given offset.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->bind($key, $value instanceof Closure ? $value : function () use ($value) {
            return $value;
        });
    }
    /**
     * Unset the value at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->bindings[$key]);
    }
    /**
     * Dynamically access container services.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }
    
    /**
     * Dynamically set container services.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }
}
