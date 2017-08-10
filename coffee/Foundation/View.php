<?php

namespace Coffee\Foundation;

class View
{
	public $root;

	public $path;

	public $theme;

	public $site;

	protected $vars = [];

	public function setTheme($theme = null ,$path = null)
	{
		$this->theme = ($theme === null) ? $this->site->theme : $theme;
		$this->path = ($path === null) ? conf('system','path') : $path;
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

	public function show($tpl,$vars = null)
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

		$ds = DIRECTORY_SEPARATOR;
		if(!$this->theme){
			$theme = sprintf("%s%s%s",$ds,$this->setTheme()->theme,$ds);
		}else{
			$theme = sprintf("%s%s%s",$ds,$this->theme,$ds);
		}
		return implode("",[$this->root,'views',$theme,$page,'.php']);
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