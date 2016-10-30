<?php
if(!defined('ABSPATH'))exit('Access denied!');

class admin extends UI{

	public function _initialize(){
		$menu = widget('admin')->get_app_menu();
		$this->assign('menu',$menu);
	}
	public function login(){
		if(widget('admin:user')->is_login())
			header( "Location:".PATH );

		session('ref_csrf_admin',APP_VALIDATE);
		$this->assign('ref_csrf_admin',APP_VALIDATE);
		$this->render('login');
	}
	public function post_login_access(){
		$data = query_vars(
			array(
				'user_name',
				'user_password',
				'csrf_id'
			)
		);
		if($data){
			$csrf_id = session('ref_csrf_admin');
			if($data['csrf_id']!=$csrf_id){
				$this->message('error','参数错误，登录失败!');
			}
			if(empty($data['user_name'])){
				$this->message('error','用户名不能为空!');
			}
			if(!widget('admin:user')->check_user_name($data['user_name'])){
				$this->message('error','用户名不存在!');
			}
			if(!isset($data['user_password']{5})){
				$this->message('error','密码不能少于六位!');
			}
			$data['user_password'] = md5($data['user_password'] . VALIDATE);
			$uid = widget('admin:user')->check_login($data['user_name'],$data['user_password']);
			if($uid){
				$user_login_time = APP_TIME;
				widget('admin:user')->update_user_id('user_login_time=$user_login_time',$uid);
				$token=$uid.','.$user_login_time.','.md5($data['user_name'].$data['user_password']);
				setcookie("token",secure_core($token,'ENCODE'),time()+3600*24*365,PATH);
				$this->message('success','登录成功!');
			}else{
				$this->message('error','您的用户名或密码不正确!');
			}
		}
	}
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
		$server['db_version']=widget()->get_db_version();
		$server['software']=$_SERVER['SERVER_SOFTWARE'];
		$server['extensions']=implode(',',get_loaded_extensions());
		$server['core_version']=VERSION;
		$this->assign('server',$server);
		$this->render('index');
	}
	public function setting(){
		# 读取配置文件
		$template='';
		$apps = widget()->get_app_lists();
		if($apps){
			foreach ($apps as $app) {
				if($app!='admin'){
					$file= ANYAPP .$app.'/config.php';
					if(is_file($file))$template.=include($file);
				}
			}
		}
		# 读取主题描述文件
		$folder = glob( ANYTHEME .'*',GLOB_ONLYDIR);
		$themes = array();
		$actived_theme = widget()->get_theme();
		foreach ($folder as $name) {
			if(is_dir($name)){
				$theme_name = str_replace(ANYTHEME,'',$name);
				if($theme_name!='admin'){
					$meta = $name.'/meta.php';
					if(file_exists($meta)){
						$theme = include $meta;
						$theme['actived'] = ($actived_theme==$theme_name)?true:false;
					}
					array_push($themes,$theme);
				}
			}
		}
		$this->assign('settings',$template);
		$this->assign('themes',$themes);
		$this->render('setting');
	}
	public function options(){
		$data = query_vars(array('app','admin','bindmenu'));
		$options = array('title'=>'','template'=>'','scripts'=>'','id'=>$data['bindmenu']);
		if(isset($data['app'])&&isset($data['admin'])&&!empty($data['admin'])){
			$file = ANYAPP.$data['app'].'/admin/'.$data['admin'];
			if(is_file($file)) $options = array_merge($options,(array) include $file);
		}
		$this->assign('options',$options);
		$this->render('options');
	}
	public function application(){
		session('app_hash',APP_VALIDATE);
		$this->assign('app_hash',APP_VALIDATE);
		$this->render('application');
	}
	public function install(){
		$app = get_query_var('app_name');
		$hash = get_query_var('hash');
		$admin = widget('admin');
		if(empty($app)&&!isset($hash)) UIkit::alert('缺少参数，安装失败!');
		if($hash!=session('app_hash')) UIkit::alert('参数错误!');
		if(!$admin->check_install($app)){
			$install = ANYAPP . $app.'/install.php';
			if(file_exists($install)){
				$query = include($install);
				if(!empty($query)){
					$admin->install($app,$query);
					UIkit::alert('安装成功!');
				}else{
					UIkit::alert('安装文件错误!');
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
		$hash = get_query_var('hash');
		$admin = widget('admin');
		if(empty($app)&&!isset($hash)) UIkit::alert('缺少参数，卸载失败!');
		if($hash!=session('app_hash')) UIkit::alert('参数错误!');
		if(!empty($app) && $admin->check_install($app)){
			$uninstall = ANYAPP . $app.'/uninstall.php';
			if(file_exists($uninstall)){
				$query = include($uninstall);
				if(!empty($query)){
					$admin->uninstall($app,$query);
					UIkit::alert('卸载成功!');
				}else{
					UIkit::alert('卸载文件错误!');
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
		$admin = widget('admin');
		if(empty($app)) UIkit::alert('缺少参数，更新失败!');
		if(!empty($app) && $admin->check_install($app)){
			$update = ANYAPP . $app.'/update.php';
			if(file_exists($update)){
				$query = include($update);
				if(!empty($query))
					$status = $admin->query($query);
				if($status) unlink($update);
				UIkit::alert(($status)?'更新成功!':'更新失败!');
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
	public function logout(){
		session('login_user',null);
		@setcookie('token',"",APP_TIME - 3600, PATH );
		header("location:".PATH.'admin/login.html');
	}
	public function get_admin_application_store(){
		$this->json(widget('admin')->get_system_apps());
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
		$status = widget('admin')->set_app_config($data);
		UIkit::alert(($status)?'网站基本信息配置成功!':'配置失败!');
	}
}