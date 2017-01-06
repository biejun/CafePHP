<?php
if( !defined('IS_ANY') ) exit('Access denied!');
/**
 *	视图处理类
 *	
 *	处理页面渲染
 */

class UI{

	public $theme = NULL;

	protected $title;

	protected $path;

	public $uri;

	public $root;

	public $static;

	public $props = [];

	public $config = [];

	protected $vars = [];

	public function __construct( $config , $uri ){

		$this->config = array_merge($config,Widget::get('admin')->getAppConfig());

		$this->path = $config['path'];

		$this->uri = $uri;

		$this->static = $this->path .'any-includes/statics/';

	}

	public function setTitle( $title ){

		$this->title = $title;

		return $this;
	}

	public function render( $page ){

		if(NULL === $this->theme ) $this->theme = $this->config['theme'];

		$this->root = $this->path.'any-themes/'.$this->theme.'/';

		$templateFile = ANYTHEME.$this->theme.DIRECTORY_SEPARATOR.$page.'.php';

		$content = $this->fetch( $templateFile );

		header('X-Powered-By:ANYPHP');

		echo $content;
	
	}

	/**
	 * 模板变量赋值
	 *
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function assign( $name , $value='' ){

		if(is_array($name)) {
		
			$this->vars = array_merge($this->vars,$name);
		
		}else {
		
			$this->vars[$name] = $value;
		}
	}

	private function fetch( $templateFile ){

		if( file_exists( $templateFile ) ){

			ob_start();

			ob_implicit_flush(0);

			$ui = $this;

			extract( $this->vars , EXTR_OVERWRITE );

			include $templateFile;

			$content = ob_get_clean();

			return $content;

		}else{

			throw new Exception('没有找到['.$templateFile.']文件!');
		}
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
	# 网页提示框，支持跳转
	public function alert($text,$url=''){
		
		echo"<script type='text/javascript'>";
		
		echo"alert('$text');";
		
		if($url!=''){
		
			echo"location.href='$url';";
		}else{
			echo"history.back();";
		}
		
		echo"</script>";
		
		exit;
	}
	public function http404(){
		
		header('HTTP/1.1 404 Not Found');
		
		header("status: 404 Not Found");

		exit;
	}
}