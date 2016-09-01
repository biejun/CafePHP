<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *	视图类
 *
 *	基于RESTful API规范构建
 */
abstract class UI{

	protected $vars = array();

	public $theme;

	private $config;

	private $theme_root;

	private $theme_url;
	
	public function __construct($app,$act){
		# 网站配置数据
		$this->config = model()->get_app_config('admin');
		if($app == 'admin' || stripos($act,'admin_')!==false){
			//if(!$this->is_admin) $this->http_404();
			$this->theme = 'admin';
		}else{
			$this->theme = model()->get_theme();
		}
		# 调用应用中的自定义函数
		if(is_file( ANYAPP .$app . '/function.php')){
			include( ANYAPP .$app . '/function.php');
		}
		$this->theme_root = ANYTHEME . $this->theme;
		$this->theme_url = PATH.'any-themes/'.$this->theme.'/';
		$this->assign('path',PATH);
		$this->assign('theme',$this->theme_url);
		$this->assign('config',$this->config);
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
					if(empty($template))$expires=0;
					echo $template;
				}else{
					$expires=0;
				}
			}elseif ($filestat['mtime'] > $expires){
				$template = file_get_contents($template);
				$pattern = array('#\{(\$[a-z0-9_]+)\}#i',
								'#\{(\$[a-z0-9_]+)\.([a-z0-9_]+)\}#i',
								'#\{if\s+(.+?)\}#',
								'#\{elseif\s+(.+?)\}#',
								'#\{else\}#',
								'#\{\/if\}#',
								'#\{import\s*\(\s*(.+)\s*\)\s*\}#i');
				$replace = array('<?php echo $1 ;?>',
								'<?php echo $1[\'$2\'] ;?>',
								'<?php if (\\1) : ?>',
								'<?php elseif(\\1) : ?>',
								'<?php else : ?>',
								'<?php endif; ?>',
								'<?php $this->render($1);?> ');
				$template = trim(str_replace('<?php exit?>','',$template));
				$template = preg_replace($pattern,$replace,$template);
				if (file_put_contents($compile, $template , LOCK_EX) === false)
					throw new \Exception('模板编译出现问题，请检查文件是否可写');
				ob_start();
				include $compile;
				$template = ob_get_contents();
				ob_end_clean();
				echo $template;
			}
		}
	}
	public function verify_post(){
		if( $_SERVER['REQUEST_METHOD']!=='POST' || empty($_SERVER['HTTP_REFERER']))
		 $this->http_404();
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
			die('{"status":"error","message":"$message"}');
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