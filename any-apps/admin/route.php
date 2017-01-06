<?php

// 管理首页

Route::get('/admin',function($ui){

	if( !is_admin() ) $ui->http404();

	$server = [
		'root'	=> $_SERVER['DOCUMENT_ROOT'],
		'name' 	=> $_SERVER['SERVER_NAME'],
		'port'	=> $_SERVER['SERVER_PORT'],
		'time' => date("Y-m-d H:i:s",$_SERVER['REQUEST_TIME']),
		'os' => PHP_OS,
		'version' => PHP_VERSION,
		'upload_size' => ini_get('upload_max_filesize'),
		'memory_usage' => UIKit::formatSize(memory_get_usage()),
		'db_version' => Widget::get('admin')->getDbVersion(),
		'core_version' => VERSION,
		'disable_functions' => ( ini_get('disable_functions') )? ini_get('disable_functions'):'无',
		'extensions' => implode(',',get_loaded_extensions()),
		'software'	=> $_SERVER['SERVER_SOFTWARE'],
	];

	$login_log = Widget::get('admin')->getLoginRecord();

	$ui->assign('bindmenu','menu-item-index');

	$ui->assign('server',$server);

	$ui->assign('login_log',$login_log);

	$ui->setTitle('管理首页')->render('index');

})->theme('admin');

// 管理登录页

Route::get('/admin/login',function($ui){

	if( is_admin() ) header("location:".PATH."admin");

	$csrf_id = md5( uniqid(rand(), true) );

	$ui->assign('ref_csrf_admin',$csrf_id);
	
	session('ref_csrf_admin',$csrf_id);

	$ui->render('login');

})->theme('admin');

// 退出登录

Route::get('/admin/logout',function(){

	if( is_admin() ){

		session('login_user',null);
		
		setcookie('any_token',"", time()-1, PATH );
		
		header("location:".PATH."admin/login.html");
	}
});

// 通用设置

Route::get('/admin/setting',function($ui){

	if( !is_admin() ) $ui->http404();

	Widget::get('admin')->getApplicationsHelpConfig();

	# 读取主题描述文件
	
	$folder = glob( ANYTHEME .'*',GLOB_ONLYDIR);
	
	$themes = array();
	
	$actived_theme = Widget::get('admin')->getThemeName();
	
	foreach ($folder as $name) {
	
		if(is_dir($name)){
	
			$theme_name = str_replace(ANYTHEME,'',$name);
	
			if($theme_name!='admin'){
	
				$meta = $name.'/meta.php';
	
				if(file_exists($meta)){
	
					$theme = include $meta;
	
					$theme['actived'] = ($actived_theme==$theme_name)?true:false;
	
				}
	
				if(isset($theme)) array_push($themes,$theme);
			}
		}
	}

	$ui->assign('themes',$themes);

	$ui->assign('bindmenu','menu-item-setting');

	$ui->setTitle('通用设置')->render('setting');

})->theme('admin');

// 应用列表

Route::get('/admin/application',function($ui){

	if( !is_admin() ) $ui->http404();

	$ui->assign('bindmenu','menu-item-application');

	$ui->setTitle('应用商店')->render('application');

})->theme('admin');

//	菜单选项

Route::get('/admin/options',function($ui){

	if( !is_admin() ) $ui->http404();

	$admin = $ui->props['admin'];

	$menu = $ui->props['menu'];

	if(isset($admin) && isset($menu)){

		$admin = explode('|',secure_core($admin));

		$menu = secure_core($menu);

		$file = ( false !== stripos($admin[2], '.php') ) ? $admin[2] : $admin[2] .'.php'; 

		$ui->assign('file', ANYAPP . $admin[0] . '/admin/'.$file);

		$ui->assign('bindmenu',$menu);

		$ui->setTitle($admin[1])->render('options');
	}
	
})->theme('admin');

// 应用安装

Route::post('/admin/application-install',function($ui){

	if( !is_admin() ) $ui->message('error','您没有权限操作！');

	$app = post_query_var('app_name');

	$config = $ui->config;

	if( isset($app) && !empty($app) ){

		if( !Widget::get('admin')->checkInstall($app) ){

			$installFile = ANYAPP . $app . '/install.php';

			if( file_exists($installFile) ){

				$query = include($installFile);

				if( !empty($query) && is_array($query) ){

					Widget::get('admin')->install( $app , $query);

					$ui->message('success','安装成功');
				}else{
					$ui->message('error','安装文件错误，请查看开发规范！');
				}
			}else{
				Widget::get('admin')->writeConfigAccess($app);
			}
		}else{
			$ui->message('error','应用已安装，安装失败！');
		}
	}
});

// 应用卸载

Route::post('/admin/application-uninstall',function($ui){

	if( !is_admin() ) $ui->message('error','您没有权限操作！');

	$app = post_query_var('app_name');

	$config = $ui->config;

	if( isset($app) && !empty($app) ){

		if( Widget::get('admin')->checkInstall($app) ){

			$uninstallFile = ANYAPP . $app . '/uninstall.php';

			if( file_exists($uninstallFile) ){

				$query = include($uninstallFile);

				if( !empty($query) && is_array($query) ){

					Widget::get('admin')->uninstall( $app , $query);

					$ui->message('success','卸载成功');
				}else{
					$ui->message('error','卸载文件错误，请查看开发规范！');
				}
			}else{
				Widget::get('admin')->deleteConfigAccess($app);
			}
		}else{
			$ui->message('error','应用已卸载，卸载失败！');
		}
	}
});

// 所有应用信息获取

Route::get('/admin/application-store',function($ui){

	if( !is_admin() ) $ui->message('error','您没有权限操作！');

	$data = Widget::get('admin')->getApplicationsDesc();

	$ui->json($data);

});

// 登录请求

Route::post('/admin/request-login',function($ui){

	$data = query_vars( 'user_name','user_password','csrf_id' );

	if( $data ){
		
		$csrf_id = session('ref_csrf_admin');
		
		if($data['csrf_id']!=$csrf_id){
		
			$ui->message('error','参数错误，登录失败!');
		}
		
		if(empty($data['user_name'])){
		
			$ui->message('error','用户名不能为空!');
		}
		
		if(!Widget::get('admin@user')->checkUserName($data['user_name'])){
		
			$ui->message('error','用户名不存在!');
		}
		
		if(!isset($data['user_password']{5})){
		
			$ui->message('error','密码不能少于六位!');
		}
		
		$data['user_password'] = md5($data['user_password'].VALIDATE);
		
		$uid = Widget::get('admin@user')->checkLogin($data['user_name'],$data['user_password']);
		
		if($uid){
		
			$user_login_time = $_SERVER['REQUEST_TIME'];

			Widget::get('admin')->loginLogRecord();
		
			Widget::get('admin@user')->updateUserId('user_login_time='.$user_login_time,$uid);
		
			$token = $uid.','.$user_login_time.','.md5($data['user_name'].$data['user_password']);
		
			setcookie("any_token",secure_core($token,'ENCODE'),time()+3600*24,PATH);

			//session('ref_csrf_admin',null);

			session('login_user',null);
		
			$ui->message('success','登录成功!');

		}else{
		
			$ui->message('error','您的用户名或密码不正确!');
		}
	}
});

// 更新管理系统配置

Route::post('/admin/update-admin-config',function($ui){

	if( !is_admin() ) $ui->message('error','您没有权限操作！');

	$data = query_vars(
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
		);

	$status = Widget::get('admin')->setAppConfig($data);

	if($status) $ui->alert('保存成功!');

});

Route::post('admin/change-password',function($ui){

	if( !is_admin() ) $ui->message('error','您没有权限操作！');

	$user = Widget::get('admin@user')->getUserInfo();

	$data = query_vars('old_password','new_password','new_password_once');

	$old_password = md5($data['old_password'].VALIDATE);

	$new_password = md5($data['new_password'].VALIDATE);

	if($old_password != $user['user_password']){
		$ui->message('error','旧密码错误!');
	}
	if($old_password == $new_password){
		$ui->message('error','请设置一个6位以上新密码!');
	}
	if($data['new_password']!=$data['new_password_once']){
		$ui->message('error','新密码两次输入不一致');
	}

	Widget::get('admin@user')->updateUserId("user_password='".$new_password."'",$user['user_id']);

	$ui->message('success','修改成功!');

});

Route::get('/admin/clean-login-log',function($ui){

	Widget::get('admin')->cleanLoginLog();

	$ui->alert('清空成功!');
});