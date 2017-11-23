<?php
/* 依赖类库 */
use Coffee\Support\Helper;

/* $this->grounp() 为相同前缀路径分组*/
$route->group('/admin',function($route){

	$route->get('/test',function(){
		echo G('database','hash');
	});

	$route->get('/index',function(){
		$this->action->on('common:assets');
		$this->render('index');
	});

	$route->get('/fonts',function(){
		$this->action->on('common:assets');
		$this->render('fonts');
	});

	$route->get('/settings',function(){
		$this->action->on('common:assets');
		$this->render('settings');
	});

	/* 路径为 /admin/login */
	$route->get('/login',function(){
		$csrf = strtoupper( md5( uniqid(rand(), true) ) );
		$suffixVersion = date('ymdHi');
		$this->view->addCSS(['grid.css','css/login.css'], $suffixVersion);
		$this->view->addJS(['ajax.js','js/login.js'], $suffixVersion);
		$this->session->login_csrf = $csrf;
		$this->view->assign('__csrf__',$csrf);
		$this->render('login');
	});

	$route->get('/logout',function(){

		//$this->render('index');
	});


	$route->group('/console',function($route){

		/* 路径为 /admin/console/backup */
		$route->get('/backup',function(){

		});

		$route->get('/cache',function(){

		});

		$route->get('/temp',function(){

		});

	});

	$route->post('/setup-config',function(){

		extract($this->request->post());

		if(!isset($step)) $this->response->sendJSON('请求参数错误',false);

		switch ($step) {
			case '1':
				$array = array();
				//$array['database']['driver'] = $driver;
				$array['database']['host'] = $dbhost;
				$array['database']['name'] = $dbname;
				$array['database']['user'] = $dbuser;
				$array['database']['password'] = $dbpassword;
				$array['database']['port'] = null;
				$array['database']['prefix'] = $dbprefix;
				$array['database']['charset'] = 'utf8';
				$array['database']['collate'] = '';
				$array['database']['hash'] = $dbhash;
				$array['database']['create'] =  (bool) $dbcreate;
				$array['cache']['location'] = 'cache/datastore';

				$file = "<?php\n return ".var_export($array,true).";";
				/* 创建系统配置文件 */
				if(!file_put_contents('config/database.php',$file,LOCK_EX)){
					$this->response->sendJSON('文件写入失败，请检查config目录属性权限是否为0777可写',false);
				}
				$this->response->sendJSON('文件写入成功！');
				break;

			case '2':
				if(empty($username) || empty($password) || empty($passwordonce))
					$this->response->sendJSON('请填写用户名和密码！',false);

				if(!isset($password{5})) $this->response->sendJSON('密码不能少于6位！',false);

				if(strcmp($password,$passwordonce) == 0){
					$user = array();
					$user['name'] = trim($username);
					$user['password'] = $password;
					$user['level'] = '10';
					$user['is_admin'] = 'true';

					if(!empty($safetycode)) $user['safetycode'] = $safetycode;

					try{
						$this->load('admin@install')->import('config/mysql.sql');
						$this->load('admin@user')->add($user);
					}catch(Exception $e){
						$this->response->sendJSON($e->getMessage(),false);
					}
					$this->load('admin@install')->lock();
					$this->response->sendJSON('创建成功！');
				}else{
					$this->response->sendJSON('两次输入的密码不一致！',false);
				}
				break;
		}
	});


	$route->group('/account',function($route){

		$route->post('/login',function(){

			$data = $this->request->post();

			$username = filter_var($data['username'],FILTER_SANITIZE_STRING);

			$password = filter_var($data['password'],FILTER_UNSAFE_RAW);

			$csrf = filter_var($data['__csrf__'],FILTER_UNSAFE_RAW);

			if($csrf != $this->session->login_csrf){
				$this->response->sendJSON('请求参数错误!',false);
			}
			if(empty($username)||!$this->load('admin@user')->checkUsername($username)){
				$this->response->sendJSON('用户不存在!',false);
			}
			if(empty($password)||!isset($password{5})){
				$this->response->sendJSON('密码不能少于六位!',false);
			}
			if($this->load('admin@user')->checkPassword($username, $password)){
				$tokens = $this->load('admin@user')->updateToken($username);
				$this->cookie->set('admin_token', $tokens['token'], $tokens['timeout']);
				// 从会话中删除已验证过得CSRF令牌
				unset($this->session->login_csrf);
				$this->response->sendJSON('登录成功!');
			}else{
				$this->response->sendJSON('用户名与密码不匹配！', false);
			}
		});
	});

	// 定义一个公用API接口
	$route->post('/api/:func',function($func){
		$args = $this->request->post();

		if(!empty($func)){
			$data = $this->load('admin@api')->run($func,$args);
			if($data){
				$this->response->sendJSON($data);
			}else{
				$this->response->sendJSON('参数错误',false);
			}
		}
	});
});