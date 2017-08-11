<?php if(!isset($route)) exit;

$route->group('/admin',function($route){

	$route->get('/console',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$loggedLogs = widget('admin@user')->getloggedLogs();

		$res->view->assign('subtitle','控制台');
		$res->view->assign('loggedLogs',$loggedLogs);
		$res->view->show('console');
	});

	$route->get('/settings',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('data',widget('admin@api')->getSiteConfig());
		$res->view->assign('subtitle','设置');
		$res->view->show('settings');
	});

	$route->get('/fonts',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','字体图标');
		$res->view->show('fonts');
	});

	$route->get('/login',function($req,$res){

		$csrf = strtoupper( md5( uniqid(rand(), true) ) );

		__session('__admin_login_csrf__',$csrf);

		$res->view->assign('__csrf__',$csrf);
		$res->view->show('login');
	});

	$route->get('/logout',function($req,$res){

		__unsetsession('__admin_name__');

		__unsetcookie('__admin_token__');

		$res->redirect('/admin/login');
	});

	$route->get('/console/backup',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$data = widget('admin@api')->getBackupFiles();

		$res->view->assign('subtitle','数据库备份');
		$res->view->assign('data',$data);
		$res->view->show('backup');
	});

	$route->get('/console/cache',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$data = widget('admin@api')->getCacheFiles();

		$totalSize = formatSize(array_sum(array_column($data,'size')));

		$buttonText = '清空缓存';

		$res->view->assign('subtitle','缓存文件');
		$res->view->assign('data',$data);
		$res->view->assign('totalSize',$totalSize);
		$res->view->assign('buttonText',$buttonText);
		$res->view->show('files');
	});

	$route->get('/console/temp',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','临时文件');
		$res->view->show('files');
	});

	$route->get('/account/profile',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','');
		$res->view->show('files');
	});

	$route->get('/account/operation',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$res->view->assign('subtitle','');
		$res->view->show('files');
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

		$action = $req->get('action');

		if('export' === $action){ // 导出
			if(widget('admin@api')->exportSQL()){
				__setcookie('__admin_notify_type__','success');
				__setcookie('__admin_notify_msg__','导出成功!');
			}else{
				__setcookie('__admin_notify_type__','error');
				__setcookie('__admin_notify_msg__','导出失败!');
			}
			$res->redirect('/admin/console/backup');
		}else if('restore' === $action){ // 还原

			$file = trim($req->post('file'));

			if(widget('admin@api')->restoreSQL($file)){
				__setcookie('__admin_notify_type__','success');
				__setcookie('__admin_notify_msg__','还原成功!');
			}else{
				__setcookie('__admin_notify_type__','error');
				__setcookie('__admin_notify_msg__','还原失败!');
			}
			$res->redirect('/admin/console/backup');
		}else if('delete' === $action){ // 删除

			$file = trim($req->post('file'));
			if(widget('admin@api')->deleteBackup($file)){
				__setcookie('__admin_notify_type__','success');
				__setcookie('__admin_notify_msg__','删除成功!');
			}else{
				__setcookie('__admin_notify_type__','error');
				__setcookie('__admin_notify_msg__','删除失败!');
			}
			$res->redirect('/admin/console/backup');
		}
	});

	$route->post('/update/setting',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

		$data = $req->post();

		if(widget('admin@config')->updateSiteConfigs($data)){
			__setcookie('__admin_notify_type__','success');
			__setcookie('__admin_notify_msg__','保存成功!');
		}

		$res->redirect('/admin/settings');
	});

	$route->post('/add/setting-options',function($req,$res){

		$req->action->on('admin:permission',$req,$res);

	});

});