<?php
/* 依赖类库 */
use Coffee\Support\Helper;

/* $this->grounp() 为相同前缀路径分组*/
$route->group('/admin',function($route){

	/* 路径为 /admin/login */
	$route->get('/login',function(){
		$this->render('login');
	});

	$route->group('/console',function($route){

		/* 路径为 /admin/console/index */
		$route->get('/:id',function($id){

			$res = Helper::validate($id,'integer');

			echo $res;
		});
	});


	/* AJAX 请求接口 */

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
});