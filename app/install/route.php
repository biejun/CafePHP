<?php
# 判断系统是否已上锁，未上锁就进行初始化配置
# 主要进行数据库连接配置和管理后台配置
if(file_exists(CONFIG . '/install.lock')) exit('The system has been installed!');

$route->group('/install',function($route){

	/* 安装系统 */
	$route->get('/',function(){
		$this->view->setView('install');
		$this->view->assign('suffixVersion',date('ymdHi'));
		$this->render('index');
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
						$this->import('install')->import('config/mysql.sql');
						$this->import('admin@user')->add($user);
					}catch(Exception $e){
						$this->response->sendJSON($e->getMessage(),false);
					}
					$this->import('install')->lock();
					$this->response->sendJSON('创建成功！');
				}else{
					$this->response->sendJSON('两次输入的密码不一致！',false);
				}
				break;
		}
	});

});