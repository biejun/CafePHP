<?php if(!isset($route)) exit;

$route->group('/admin',function($route){

	$route->get('/console',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','控制台');
		// $res->view->assign('logs',widget('admin@log')->getLogs());
		// $res->view->assign('operates',widget('admin@operate')->getOperates());
		$res->view->show('console');
	});

	$route->get('/settings',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('data',widget('admin@config')->getSiteConfig());
		$res->view->assign('subtitle','设置');
		$res->view->show('settings');
	});

	$route->get('/fonts',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','字体图标');
		$res->view->show('fonts');
	});

	$route->get('/login',function($req,$res){

		if( widget('admin@user')->isAdmin() ){
			$res->redirect('/admin/console');
		}

		$csrf = strtoupper( md5( uniqid(rand(), true) ) );

		__session('__admin_login_csrf__',$csrf);

		$res->view->assign('__csrf__',$csrf);
		$res->view->show('login');
	});

	$route->get('/logout',function($req,$res){

		session_unset();
		
		session_destroy();

		// __unsetsession('__admin_name__');

		__unsetcookie('__admin_token__');

		$res->redirect('/admin/login');
	});

	$route->get('/console/backup',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$data = widget('admin@console')->getBackupFiles();

		$res->view->assign('subtitle','数据库备份');
		$res->view->assign('data',$data);
		$res->view->show('backup');
	});

	$route->get('/console/cache',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$data = widget('admin@console')->getCacheFiles();
		$totalSize = formatSize(array_sum(array_column($data,'size')));

		$res->view->assign('subtitle','缓存文件');
		$res->view->assign('data',$data);
		$res->view->assign('totalSize',$totalSize);
		$res->view->assign('buttonText','清空缓存');
		$res->view->assign('type','cache');
		$res->view->show('files');
	});

	$route->get('/console/temp',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','临时文件');
		$res->view->assign('buttonText','清空临时文件');
		$res->view->assign('type','temp');
		$res->view->assign('data',array());
		$res->view->assign('totalSize',0);
		$res->view->show('files');
	});

	$route->get('/account/profile',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$do = $req->get('do');

		$res->view->assign('subtitle','个人资料');
		$res->view->assign('do',$do);
		$res->view->show('profile');
	});

	$route->get('/account/operation',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','用户管理');
		$res->view->show('operation');
	});

	$route->get('/account/add',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','');
		$res->view->show('files');
	});

	$route->post('/post/login',function($req,$res){

		$username = $req->post('username');
		$password = $req->post('password');
		$csrf = $req->post('__csrf__');

		if( $csrf != __session('__admin_login_csrf__') ){
			$res->json('请求参数错误',false);
		}

		if(empty($username)){
			$res->json('用户名不能为空!',false);
		}

		if(!isset($password{5})){
			$res->json('密码不能少于六位!',false);
		}

		if(!widget('admin@user')->checkUserName($username)){
			$res->json('您没有权限登录!',false);
		}

		if(widget('admin@user')->checkPassword($username,$password)){
			widget('admin@user')->updateLoginTime($username);
			// 从会话中删除已验证过得CSRF令牌
			__unsetsession('__admin_login_csrf__');
			$res->json('登录成功',true);
		}else{
			$res->json('密码错误',false);
		}
	});

	$route->post('/post/backup/:action',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$action = $req->get('action');

		if('export' === $action){ // 备份

			if(widget('admin@console')->exportSQL()){
				widget('admin@operate')->setOperate("备份了数据库");
				$req->action->on('admin:notify','success','备份成功!');
			}else{
				$req->action->on('admin:notify','error','备份失败!');
			}
		}else if('restore' === $action){ // 还原

			$file = trim($req->post('file'));

			if(widget('admin@console')->restoreSQL($file)){
				widget('admin@operate')->setOperate("还原了\"{$file}\"数据库备份文件");
				$req->action->on('admin:notify','success','还原成功!');
			}else{
				$req->action->on('admin:notify','error','还原失败!');
			}
		}else if('delete' === $action){ // 删除

			$file = trim($req->post('file'));

			if(widget('admin@console')->deleteBackup($file)){
				widget('admin@operate')->setOperate("删除了\"{$file}\"数据库备份文件");
				$req->action->on('admin:notify','success','删除成功!');
			}else{
				$req->action->on('admin:notify','error','删除失败!');
			}
		}

		$res->goBack();
	});

	$route->post('/update/setting',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$data = $req->post();

		if(widget('admin@config')->updateSiteConfigs($data)){
			widget('admin@operate')->setOperate('更新了站点设置');
			$req->action->on('admin:notify','success','保存成功!');
		}

		$res->goBack();
	});

	$route->post('/add/setting-options',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

	});

	$route->post('/delete/clean',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$type = $req->post('type');

		if(widget('admin@console')->cleanFiles($type)){
			$req->action->on('admin:notify','success','清空成功!');
		}else{
			$req->action->on('admin:notify','error','清空失败!');
		}

		$res->goBack();
	});

	$route->post('/delete/file',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$filePath = $req->post('filePath');

		if(file_exists($filePath)){

			if(unlink($filePath)){
				$req->action->on('admin:notify','success','删除成功!');
			}else{
				$req->action->on('admin:notify','error','删除失败!');
			}
		}else{

			$req->action->on('admin:notify','error','删除失败，请确定文件是否存在!');
		}

		$res->goBack();
	});

	$route->post('/update/password',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$oldPassword = trim($req->post('oldPassword'));
		$newPassword = trim($req->post('newPassword'));
		$newPasswordOnce = trim($req->post('newPasswordOnce'));

		if(empty($oldPassword)){
			$req->action->on('admin:notify','error','旧密码不能为空!');
			$res->goBack();
		}
		if(empty($newPassword)){
			$req->action->on('admin:notify','error','新密码不能为空!');
			$res->goBack();
		}
		if(empty($newPasswordOnce)){
			$req->action->on('admin:notify','error','请确认密码!');
			$res->goBack();
		}
		if($oldPassword === $newPassword){
			$req->action->on('admin:notify','error','请设置一个新的密码!');
			$res->goBack();
		}

		if($newPassword != $newPasswordOnce){
			$req->action->on('admin:notify','error','两次输入的新密码不一致!');
			$res->goBack();
		}

		if(widget('admin@user')->updatePassword($oldPassword,$newPassword)){
			widget('admin@operate')->setOperate('修改了账户密码');
			$req->action->on('admin:notify','error','设置成功!');
			$res->goBack();
		}else{
			$req->action->on('admin:notify','error','旧密码不正确!');
			$res->goBack();
		}
	});

	// 定义一个公用API接口
	$route->post('/api/:func',function($req,$res){

		$func = $req->get('func');

		if(!empty($func)){
			$data = widget('admin@api')->run($func,$req->post());
			if($data){
				$res->json($data,true);
			}else{
				$res->json('参数错误',false);
			}
		}
	});

});