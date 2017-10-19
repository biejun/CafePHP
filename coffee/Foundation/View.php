<?php

namespace Coffee\Foundation;

class View
{

	public $lang = '';

	public $path;

	public $theme;

	public $ext = '.php';

	public $site;

	protected $vars = [];

	public function setTheme($theme = null ,$path = null)
	{
		$this->theme = ($theme === null) ? ($this->site->theme) ? $this->site->theme : '' : $theme;
		$this->path = ($path === null) ? G('system','path') : $path;
		return $this;
	}

	public function setExt($ext)
	{
		$this->ext = $ext;
		return $this;
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
			echo $content;
		}else{
			throw new \Exception("缺少模板文件$tpl");
		}
	}

	private function getThemePath($page)
	{
		if(!$this->theme){
			$theme = VIEWS . "/{$this->setTheme()->theme}/{$page}";
		}else{
			$theme = VIEWS . "/{$this->theme}/{$page}";
		}
		return $theme.$this->ext;
	}
	/**
	 * 模板渲染
	 *
	 * @param string $tpl 模板路径
	 * @param string $vars 模板参数
	**/
	public function render($tpl,$vars)
	{
		$tpl = $this->getThemePath($tpl);
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