<?php

$route->get('/',function(){
	echo 'Hello World!';
});

# 判断系统是否已上锁，未上锁就进行初始化配置
# 主要进行数据库连接配置和管理后台配置
if(!file_exists(CONFIG . '/system.lock')){

	/* 安装系统 */
	$route->get('/install',function(){
		$this->view->setView('install');
		$this->view->assign('suffixVersion',date('ymdHi'));
		$this->render('index');
	});

	$route->post('/install',function(){

		extract($this->request->post());

		if(isset($do)){

			if($do === '1'){
				$array = array();
				//$array['database']['driver'] = $driver;
				$array['database']['host'] = $dbhost;
				$array['database']['name'] = $dbname;
				$array['database']['user'] = $dbuser;
				$array['database']['password'] = $dbpassword;
				$array['database']['port'] = null;
				$array['database']['prefix'] = $dbprefix;
				$array['database']['charset'] = 'utf8';
				$array['database']['collate'] = 'utf8_general_ci';
				$array['database']['create'] =  (bool) $dbcreate;
				$array['cache']['location'] = 'cache/datastore';

				$file = "<?php\n return ".var_export($array,true).";";
				/* 创建系统配置文件 */
				if(!file_put_contents('config/database.php',$file,LOCK_EX)){
					$this->response->json('文件写入失败，请检查config目录属性权限是否为0777可写',false);
				}
				$this->response->json('文件写入成功！');
			}

			if($do === '2'){

				if(!empty($username) && !empty($password) && !empty($passwordonce)){

					if(isset($password{5})){

						if(strcmp($password,$passwordonce) == 0){

							try{
								$sql = file_get_contents(CONFIG .'mysql.sql');
								if(!$sql){
									throw new Exception("没有找到config/mysql.sql文件", 1);
								}
								$sql = str_replace('%prefix%',G('database','prefix'), $sql);
								$sql = str_replace('%charset%',G('database','charset'), $sql);
								$sql = explode(';', $sql);
								$this->import('admin')->createTables($sql);
								$this->import('admin')->add($username,$password);
							}catch(Exception $e){
								$this->response->json($e->getMessage(),false);
							}

							$this->response->json('创建成功！');
						}else{
							$this->response->json('两次输入的密码不一致！',false);
						}
					}else{
						$this->response->json('密码不能少于6位！',false);
					}
				}
			}
		}

		$this->response->json('请求参数错误',false);
	});
}