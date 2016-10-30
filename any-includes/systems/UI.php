<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 * 视图处理类
 *
 * @package UI
 * @version 1.0.0
 */
abstract class UI{

	private $theme_root = '';

	private $theme_url;

	public $theme = '';

	public $config = array();

	protected $vars = array();
	
	public function __construct($theme = ''){

		# 网站配置数据
		$this->config = widget()->get_apps_config();

		if(!empty($theme)){

			$this->theme = $theme;
			$this->theme_root = ANYTHEME . $this->theme;
			$this->theme_url = PATH.'any-themes/'.$this->theme.'/';
			
			$this->assign('path',PATH);
			$this->assign('theme',$this->theme_url);
			$this->assign('config',$this->config);
		}
		if(method_exists($this, '_initialize'))
			$this->_initialize();
	}
	public function assign($key,$value=''){
		if(is_array($key)||is_object($key)){
			foreach ($key as $k => $v) {
				if(!empty($k))
					$this->vars[$k] = $v;
			}
		}else{
			if(!empty($key))
				$this->vars[$key] = $value;
		}
	}
	public function render($name,$data=''){
		$template = $this->theme_root.'/'.$name.'.php';
		if(file_exists($template)){
			make_dir(ANYINC . 'cache/template');
			$compile = ANYINC . 'cache/template/'.md5($this->theme.$name).'.php';
			$filestat = @stat($compile);
			$expires = $filestat['mtime'];
			$filestat = stat($template);
			if(is_array($data)){
				$this->vars = array_merge($this->vars,$data);
			}
			extract($this->vars);

			if ($filestat['mtime'] <= $expires){
				if (file_exists($compile)){
					ob_start();
					include $compile;
					$template = ob_get_contents();
					ob_end_clean();
					if(empty($template)) $expires=0;
					echo $template;
				}else{
					$expires=0;
				}
			}elseif ($filestat['mtime'] > $expires){
				$template = file_get_contents($template);
				if(strpos($template,"\xEF\xBB\xBF")!==false)
					$template = str_replace("\xEF\xBB\xBF",'',$template); # 干掉微软的BOM头
				$pattern = array('#\{(\$[a-z0-9_]+)\}#i',
								'#\{(\$[a-z0-9_]+)\.([a-z0-9_]+)\}#i',
								'#\{if\s+(.+?)\}#',
								'#\{elseif\s+(.+?)\}#',
								'#\{else\}#',
								'#\{\/if\}#',
								'#\{foreach\s+(\S+)\s+as\s+(\S+)\}#',
								'#\{\/foreach\}#',
								'#\{import\s*\(\s*(.+)\s*\)\s*\}#i');
				$replace = array('<?php echo $1 ;?>',
								'<?php echo $1[\'$2\'] ;?>',
								'<?php if (\\1) : ?>',
								'<?php elseif(\\1) : ?>',
								'<?php else : ?>',
								'<?php endif; ?>',
								'<?php if(is_array(\\1)) foreach(\\1 as \\2) : ?>',
								'<?php endforeach; ?>',
								'<?php $this->render($1);?> ');
				$template = trim(str_replace('<?php exit?>','',$template));
				$template = preg_replace($pattern,$replace,$template);
				if (file_put_contents($compile, $template , LOCK_EX) === false)
					throw new Exception('模板编译出现问题，请检查文件是否可写');
				ob_start();
				include $compile;
				$template = ob_get_contents();
				ob_end_clean();
				echo $template;
			}
		}
	}
	public function http_301($url){
		header('HTTP/1.1 301 Moved Permanently');
		Header( "Location:$url");
		exit;
	}
	public function http_404(){
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$this->render('404');
		exit;
	}
	public function message($status,$message='',$data=array(),$charset='utf-8'){
		header('Content-Type: application/json;charset='.$charset);
		if($status == 'error'){
			die('{"status":"error","message":"'.$message.'"}');
		}else{
			echo json_encode(array('status'=>'success','message'=>$message,'data'=>$data));
		}
	}
	public function json($data,$code=200,$charset='utf-8'){
		$res = new Response();
		$res->status($code)
			->header('Content-Type', 'application/json; charset='.$charset)
			->write(json_encode($data))
			->send();
	}
	public function jsonp($data,$param='jsonp',$code=200,$charset='utf-8'){
		$callback = get_query_var($param);
		$res = new Response();
		$res->status($code)
			->header('Content-Type', 'application/json; charset='.$charset)
			->write($callback.'('.json_encode($data).')')
			->send();
	}
	public function __set($name, $value){
		$this->vars[$name] = $value;
	}
	public function __get($name){
		return $this->vars[$name];
	}
	public function __call($method,$args){
		$this->http_404();
	}
	# 检测模板变量是否设置
	public function __isset($name){
		return isset($this->vars[$name]);
	}
	# 初始化回调
	public function _initialize(){}	
}