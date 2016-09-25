<?php
class admin extends UI{

	public function index(){
		$server=array();
		$server['port']=$_SERVER['SERVER_PORT'];
		$server['time']=date("Y-m-d H:i:s",$_SERVER['REQUEST_TIME']);
		$server['os']=@PHP_OS;
		$server['version']=@PHP_VERSION;
		$server['root']=$_SERVER['DOCUMENT_ROOT'];
		$server['name']=$_SERVER['SERVER_NAME'];
		$server['upload']=@ini_get('upload_max_filesize');
		$server['memory_usage']=UIKit::format_size(memory_get_usage());
		$server['disable_functions']=(ini_get('disable_functions'))?ini_get('disable_functions'):'无';
		$server['db_version']=model()->get_db_version();
		$server['software']=$_SERVER['SERVER_SOFTWARE'];
		$server['extensions']=implode(',',get_loaded_extensions());
		$server['core_version']='1.0.0 Beta';
		$this->assign('server',$server);
		$this->render('index');
	}
	public function setting(){
		$template='';
		$apps=model()->get_app_lists();
		if($apps){
			foreach ($apps as $app) {
				if($app!='admin'){
					$file= ANYAPP .$app.'/config.php';
					if(is_file($file))$template.=include($file);
				}
			}
		}
		$this->assign('setting',$template);
		$this->render('setting');
	}
	public function options(){
		$data = query_vars(array('app','admin'));
		$options = array(
			'title' => '',
			'template' => '',
			'vue' => ''
		);
		if(isset($data['app'])&&isset($data['admin'])&&!empty($data['admin'])){
			$file = ANYAPP.$data['app'].'/admin/'.$data['admin'];
			if(is_file($file)) $options = include $file;
		}
		$this->assign('options',$options);
		$this->render('options');
	}
	public function application(){
		$array = model('admin')->get_system_apps();
		$this->assign('apps',json_encode($array));
		$this->render('application');
	}
	public function theme(){
		$folder = glob( ANYTHEME .'*');
		$themes = array();
		foreach ($folder as $name) {
			if(is_dir($name)){
				$theme_name = str_replace(ANYTHEME,'',$name);
				if($theme_name!='admin'){
					$meta = $name.'/meta.php';
					if(file_exists($meta)){
						$item = include $meta;
						$item['themeName'] = $theme_name;
						$item['themeThumb'] = PATH.'any-themes/'.$theme_name.'/screenshot.png';
						array_push($themes,$item);
					}
				}
			}
		}
		$this->assign('current',model()->get_theme());
		$this->assign('themes',json_encode($themes));
		$this->render('theme');
	}
	public function fontello(){
		$this->render('fontello');
	}
	public function install(){
		$app = get_query_var('app_name');
		$admin = model('admin');
		if(empty($app)){
			UIkit::alert('缺少参数，安装失败!');
		}
		if(!$admin->check_install($app)){
			$install = ANYAPP . $app.'/install.php';
			if(file_exists($install)){
				$query = include($install);
				if(!empty($query)){
					$admin->query($query);
					$admin->install($app);
					UIkit::alert('安装成功!');
				}
			}else{
				UIkit::alert('没有找到安装文件!');
			}
		}else{
			UIkit::alert('安装失败!');
		}
	}
	public function uninstall(){
		$app = get_query_var('app_name');
		$admin = model('admin');
		if(empty($app)){
			alert('缺少参数，卸载失败!');
		}
		if($admin->check_install($app)){
			$uninstall = ANYAPP . $app.'/uninstall.php';
			if(file_exists($uninstall)){
				$query = include($uninstall);
				if(!empty($query)){
					$admin->query($query);
					$admin->uninstall($app);
					UIkit::alert('卸载成功!');
				}
			}else{
				UIkit::alert('没有找到卸载文件!');
			}
		}else{
			UIkit::alert('您还没有安装这个程序!');
		}
	}
	public function update(){
		$app = get_query_var('app_name');
		$admin = model('admin');
		if(empty($app)){
			alert('缺少参数，更新失败!');
		}
		if($admin->check_install($app)){
			$update = ANYAPP . $app.'/update.php';
			if(file_exists($update)){
				$query = include($update);
				if(!empty($query)&&is_array($query))
					$admin->query($query);
				unlink($update);
				UIkit::alert('更新成功!');
			}else{
				UIkit::alert('没有找到更新文件!');
			}
		}else{
			UIkit::alert('您还没有安装这个程序!');
		}		
	}
	public function clear_files(){
		UIkit::alert('清理成功');
	}
	public function post_admin_config(){
		$data = query_vars(
				array(
					'title',
					'subtitle',
					'keywords',
					'description',
					'statcode',
					'notice',
					'ad',
					'icp',
					'smtp_server',
					'smtp_port',
					'smtp_user',
					'smtp_password',
					'smtp_email'
				)
			);
		$status = model('admin')->set_app_config($data);
		UIkit::alert(($status)?'网站基本信息配置成功!':'配置失败!');
	}
	public function get_app_menu(){
		$this->json(model('admin','api')->get_app_menu());
	}
}