<?php namespace Cafe\Foundation;
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

use Cafe\Minify;

/**
 * 应用前端视图渲染
 *
 * @package Cafe\Foundation\View
 * @since 0.0.5
 */

class View
{
    public $lang = 'zh-CN';

    public $path = PATH;

    public $ext = '.php';

    protected $currentView = '';

    protected $viewPath;

    protected $vars = [];

    /* 视图文件存放目录 */
    public function folder($view = null ,$path = null)
    {
        if(!is_null($path)) $this->path = $path;

        $this->currentView = is_null($view) ? '': $view;
        $this->viewPath = $this->pathJoin('view', $this->currentView);
        return $this;
    }

    /* 视图文件后缀 */
    public function setExt($ext)
    {
        $this->ext = $ext;
        return $this;
    }

    /* 压缩JS $path 值为文件路径或者JS代码*/
    public function minifyJS($mergeFiles, $outputFile, $version = '1.0.0')
    {
		if( IS_DEVELOPMENT ) {
		  $minifier = new Minify\JS($mergeFiles);
		  $minifier->minify($this->pathJoin(STATIC_ASSETS, $outputFile));
		}
		$file = $this->fileJoinVersion($this->pathJoin(STATIC_ASSETS_DIR, $outputFile), $version);
		echo "<script type=\"text/javascript\" src=\"$file\"></script>\n";
    }

    /* 压缩CSS $path 值为文件路径或者CSS代码*/
    public function minifyCSS($mergeFiles, $outputFile, $version = '1.0.0')
    {
		if( IS_DEVELOPMENT ) {
		  $minifier = new Minify\CSS($mergeFiles);
		  $minifier->minify($this->pathJoin(STATIC_ASSETS, $outputFile));
		}
		$file = $this->fileJoinVersion($this->pathJoin(STATIC_ASSETS_DIR, $outputFile), $version);
		echo "<link rel=\"stylesheet\" href=\"$file\">\n";
    }
	
	public function sources($filePath) 
	{
		return $this->pathJoin(SOURCES_DIR, $filePath);
	}

    /* 将多个参数组合成一个路径 */
    public function pathJoin()
    {
        $path = array();
        $args = func_get_args();
        if(count($args) > 0){
            foreach ($args as $key => $value) {
                $path[] = $value;
            }
        }
        return $this->path . join('/', $path);
    }

    /* 给文件资源加上版本号 */
    public function fileJoinVersion($filePath, $suffixVersion = null)
    {
        return $filePath . (is_null($suffixVersion) ? '' : '?v='.$suffixVersion);
    }

    /* 将数据赋值到视图中 */
    public function assign($key,$value='')
    {
        if(is_array($key)) {
            $this->vars = array_merge($this->vars,$key);
        }else {
            $this->vars[$key] = $value;
        }
        return $this;
    }
    /* 读取一个模板 */
    public function tpl($tpl,$vars = null)
    {
        if (func_num_args () > 2) {
            $vars = func_get_args ();
            array_shift ($vars);
        } elseif ($vars === null) {
            $vars = $this->vars;
        }
        if($content = $this->render($tpl,$vars)){
            return $content.PHP_EOL;
        }else{
            throw new \Exception("{$this->viewPath}目录下缺少模板文件'{$tpl}{$this->ext}'");
        }
    }

    /* 获取视图文件路径 */
    private function getViewFilePath($page)
    {
        if(!$this->currentView){
            $view = VIEW . "/{$this->setView()->currentView}/{$page}";
        }else{
            $view = VIEW . "/{$this->currentView}/{$page}";
        }
        return $view.$this->ext;
    }
    /**
     * 模板渲染
     *
     * @param string $tpl 模板路径
     * @param string $vars 模板参数
    **/
    public function render($tpl,$vars)
    {
        $tpl = $this->getViewFilePath($tpl);
        if( file_exists( $tpl ) ){
            $obLevel = ob_get_level();
            ob_start();
            ob_implicit_flush(0);
            extract( $vars, EXTR_OVERWRITE );
            // 使用 try/catch 捕获异常，防止局部代码发生错误前可能会出现的任何意外输出
            try
            {
                include $tpl;
            }
            catch (Exception $e)
            {
                $this->handleViewException($e, $obLevel);
            }
            return ob_get_clean();
        }
        return false;
    }

    protected function handleViewException($e, $obLevel)
    {
        while (ob_get_level() > $obLevel)
        {
            ob_end_clean();
        }
        throw $e;
    }
}