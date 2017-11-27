<?php
/* 依赖类库 */
use Coffee\Support\Helper;

/* $this->grounp() 为相同前缀路径分组*/
$route->group('/admin',function($route){

	$route->get('/test',function(){
		$this->action->on('check:login');
		echo $this->view->account->name;
	});

	$route->get('/index',function(){
		$this->action->on('check:login');
		$this->action->on('common:assets');
		$this->render('index');
	});

	$route->get('/fonts',function(){
		$this->action->on('check:login');
		$this->action->on('common:assets');
		$this->render('fonts');
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

	/* 退出登录 */
	$route->get('/logout',function(){
		$this->session->destroy();
		$this->cookie->delete('user_login_token');
		$this->response->redirect(PATH .'admin/login');
	});

	/* 控制台页面 */
	$route->group('/console',function($route){

		/* 路径为 /admin/console/backup */
		$route->get('/backup',function(){

		});

		$route->get('/cache',function(){

		});

		$route->get('/temp',function(){

		});

	});

	/* 系统设置 */
	$route->group('/options',function($route){

		$route->get('/config',function(){
			$this->action->on('check:login');
			$this->action->on('common:assets');
			$this->render('options-config');
		});
	});

	/* 程序安装 */
	$route->post('/setup-config',function(){

		$this->action->on('check:login');

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
						$this->load('admin@users')->add($user);
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

		/* 登录账号 */
		$route->post('/login',function(){

			$data = $this->request->post();

			$username = filter_var($data['username'],FILTER_SANITIZE_STRING);

			$password = filter_var($data['password'],FILTER_UNSAFE_RAW);

			$csrf = filter_var($data['__csrf__'],FILTER_UNSAFE_RAW);

			if($csrf != $this->session->login_csrf){
				$this->response->sendJSON('请求参数错误!',false);
			}
			if(empty($username)||!$this->load('admin@users')->checkUsername($username)){
				$this->response->sendJSON('用户不存在!',false);
			}
			if(empty($password)||!isset($password{5})){
				$this->response->sendJSON('密码不能少于六位!',false);
			}
			if($this->load('admin@users')->checkPassword($username, $password)){
				$tokens = $this->load('admin@users')->updateToken($username);
				$this->cookie->set('user_login_token', $tokens['token'], strtotime($tokens['timeout']));
				// 从会话中删除已验证过得CSRF令牌
				unset($this->session->login_csrf);
				$this->response->sendJSON('登录成功!');
			}else{
				$this->response->sendJSON('用户名与密码不匹配！', false);
			}
		});

		/* 添加账号 */
		$route->post('/add',function(){

		});

		/* 删除账号 */
		$route->post('/delete',function(){

		});
	});

	/* 定义一个公用API接口 */
	$route->post('/api/:func',function($func){

		$this->action->on('check:login');

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