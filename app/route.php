<?php

$route->get('/',function(){

	$demo = $this->import('admin@api');
	$post = $this->import('post@api');
	$post->hello();
});

/* 安装系统 */
$route->get('/install',function(){
	$this->view->setView('install');
	$this->view->assign('suffixVersion',date('ymdHi'));
	$this->render('index');
});

$route->post('/install',function(){

	$do = $this->request->post('do');

	if(!is_null($do)){
		if($do === '1'){
			$data = $this->request->post();
			extract($data);
			$array = $result = array();
			//$array['database']['driver'] = $driver;
			$array['database']['host'] = $dbhost;
			$array['database']['name'] = $dbname;
			$array['database']['user'] = $dbuser;
			$array['database']['password'] = $dbpassword;
			$array['database']['port'] = null;
			$array['database']['prefix'] = $dbprefix;
			$array['database']['charset'] = 'utf8';
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

		}
	}
	$this->response->json('请求参数错误',false);	
});