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

use Cafe\Foundation\App;
use League\Plates\Engine as TemplateEngine;
use League\Plates\Extension\ExtensionInterface;
use Cafe\Support\Arr;

class ViewExtension implements ExtensionInterface
{    
    protected $app;

    public function __construct(App $app)
    {
       $this->app = $app;
    }
    /**
     * Register extension function.
     * @param Engine $engine
     * @return null
     */
    public function register(TemplateEngine $engine)
    {
        $engine->registerFunction('u', [$this, 'u']);
        $engine->registerFunction('compress', [$this, 'compress']);
        $engine->registerFunction('lang', [$this, 'lang']);
        $engine->registerFunction('options', [$this, 'options']);
        $engine->registerFunction('account', [$this, 'account']);
    }
    
    // 前端路径生成
    public function u($url = '')
    {
        return u($url);
    }
    // 前端代码压缩
    public function compress($inputPath = '', $outputPath = '')
    {
        return new Compress($this->app, $inputPath, $outputPath);
    }
    // 页面语言
    public function lang()
    {
        return $this->app->getConfig('locale');
    }
    // 读取站点配置
    public function options($option)
    {
        return Arr::get($this->app->getConfig('options'), $option);
    }
    // 获取登录用户信息
    public function account()
    {
        return model('Account');
    }
}

class View
{
    protected $template;
    
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
        
        $this->template = new TemplateEngine();
        
        $this->template->loadExtension(new ViewExtension($app));
        
        $this->folder($app->getConfig('template'));
        
        /* 添加一个公共模板目录，主要用于页面布局 */
        $this->setTemplateFolder('common');
    }
    /* 添加一个模板目录 */
    public function setTemplateFolder($template)
    {
        $this->template->addFolder($template,$this->app->viewPath($template));
    }
    
    public function folder($folderName)
    {
        $this->template->setDirectory($this->app->viewPath($folderName));
        return $this;
    }
    /**
     * Proxies all methods to the template.
     *
     * @param string  $method
     * @param mixed[] $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->template, $method], $args);
    }
}
