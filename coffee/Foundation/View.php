<?php
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

/**
 * 应用前端视图渲染
 *
 * @package Coffee\Foundation\View
 * @since 0.0.5
 */
namespace Coffee\Foundation;

class View
{
	public $lang = 'zh-CN';

	public $path = PATH;

	public $ext = '.php';

	public $assets = array();

	protected $currentView = '';

	protected $currentViewPath;

	protected $vars = [];

	public function setView($view = null ,$path = null)
	{
		if(!is_null($path)) $this->path = $path;

		$this->currentView = is_null($view) ? '': $view;
		$this->currentViewPath = $this->pathJoin('views', $this->currentView);
		return $this;
	}

	public function setExt($ext)
	{
		$this->ext = $ext;
		return $this;
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

	/* 将多个参数组合到当前视图文件夹路径中 */
	public function viewPathJoin()
	{
		$path = array();
		$args = func_get_args();
		if(count($args) > 0){
			foreach ($args as $key => $value) {
				$path[] = $value;
			}
		}
		return $this->currentViewPath. '/' . join('/', $path);
	}

	/* 给文件资源加上版本号 */
	public function fileJoinVersion($filePath, $suffixVersion = null)
	{
		return $filePath . (is_null($suffixVersion) ? '' : '?v='.$suffixVersion);
	}

	public function assign($key,$value='')
	{
		if(is_array($key)) {
			$this->vars = array_merge($this->vars,$key);
		}else {
			$this->vars[$key] = $value;
		}
		return $this;
	}

	public function tpl($tpl,$vars = null)
	{
		if (func_num_args () > 2) {
			$vars = func_get_args ();
			array_shift ($vars);
		} elseif ($vars === null) {
			$vars = $this->vars;
		}
		if($content = $this->render($tpl,$vars)){
			return $content;
		}else{
			throw new \Exception("{$this->currentViewPath}目录下缺少模板文件'{$tpl}{$this->ext}'");
		}
	}

	/* 获取视图文件路径 */
	private function getViewFilePath($page)
	{
		if(!$this->currentView){
			$view = VIEWS . "/{$this->setView()->currentView}/{$page}";
		}else{
			$view = VIEWS . "/{$this->currentView}/{$page}";
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
			ob_start();
			ob_implicit_flush(0);
			extract( $vars,EXTR_OVERWRITE );
			require( $tpl );
			return ob_get_clean();
		}
		return false;
	}
}