<?php

class AdminWidget extends Widget{

	public $activate_cache = true;
	
	# 检查程序是否已安装
	public function checkInstall($app){
		
		if(empty($app)) return false;
		
		$cache = Core::$cache;

		$apps = $cache->read('apps');
		
		return in_array($app,$apps);
	}
	// 数据库备份导出
	public function export(){

		return $this->db->export();
	}
	# 执行应用程序数据库语句
	public function query($query){
		
		if(is_array($query)){
		
			foreach ($query as $sql) {
		
				$this->db->query($sql);
			}
		
			return true;
		
		}
		return false;
	}
	# 执行应用程序安装文件
	public function install($app,$query){
		
		$this->query($query);

		$this->writeConfigAccess($app);
	}
	// 写入应用配置入口
	public function writeConfigAccess($app){

		$cache = Core::$cache;

		$apps = $cache->read('apps');

		$apps[] = $app;
		
		$this->db->update($this->table,"config_value='".implode("|",$apps)."'","config_key='apps'");
		
		$this->db->insert($this->table,array('config_key'=>$app));
		
		$cache->deleteCache($app.'_config');
		
		$cache->deleteCache('apps');
	}
	# 执行应用程序卸载文件
	public function uninstall($app,$query){
		
		$this->query($query);

		$this->deleteConfigAccess($app);
		
	}
	// 删除应用配置入口
	public function deleteConfigAccess($app){

		$cache = Core::$cache;

		$apps = $cache->read('apps');

		$apps = array_diff($apps,array($app));
		
		$this->db->update($this->table,"config_value='".implode("|",$apps)."'","config_key='apps'");
		
		$this->db->delete($this->table,"config_key='$app'");
		
		$cache->deleteCache($app.'_config');
		
		$cache->deleteCache('apps');
	}
	# 获取应用程序状态
	public function getAppStatus($app){
		
		$array = array();
		
		$path = PATH.'admin/';
		
		if($this->checkInstall($app)){
		
			$array['status'] = '卸载';
		
			$array['path'] = $path.'uninstall';
		
		}else{
		
			if(is_file(ANYAPP.$app.'/update.php')){
		
				$array['status'] = '更新';
		
				$array['path'] = $path.'update';
			}else{
		
				$array['status'] = '安装';
		
				$array['path'] = $path.'installl';
			}
		}
		return $array;
	}
	# 获取应用程序列表
	public function getApplicationsDesc(){

		$cache = Core::$cache;

		$apps = Core::$apps;

		$array=array();
		
		if(!empty($apps)){
		
			foreach ($apps as $app) {

				$file = ANYAPP. $app. '/description.php';

				if(is_file($file)){

					$info = include $file;
					
					if(!empty($info)){

						$meta = array(
							'app' 	=> $info['app'],
							'icon'	=> $info['icon'],
							'name'	=> $info['name'],
							'description' => $info['description'],
							'version'	=>	$info['version'],
							'author'	=>	$info['author'],
							'date'		=>	$info['date'],
							'special'	=>	$info['special'],
							'options'	=>	$info['options'],
							'install'	=>	$this->getAppStatus($info['app']),
						);
			
						$array[] = $meta;
					}
				}
			}

			$cache->write('app_descriptions',$array);
		}

		return $array;
	}
	# 获取应用程序的管理菜单 ( 已安装应用 )
	public function getApplicationsAdminMenu(){

		$cache = Core::$cache;

		$description = $cache->read('app_descriptions');

		if(empty($description)) $description = $this->getApplicationsDesc();

		$apps = $cache->read('apps');

		$array = array();

		foreach ($description as $info) {

			foreach ($apps as $key => $app) {


				if( $info['app']==$app && isset($info['options'])){

					$item=array();

					foreach($info['options'] as $id => $v){

						list($title,$url) = $v;

						if(!empty($url)){

							$hash = secure_core($app.'|'.$title.'|'.$url,'ENCODE');

							$menu = secure_core('menu-item-'.$key.'-child-'.$id,'ENCODE');

							$url = PATH.'admin/options?admin='.urlencode($hash).'&menu='.urlencode($menu);
						}
						
						$item[] = compact('title','url','id');
					}
					
					$array[$key]['id'] = $key;
					
					$array[$key]['name']=$info['name'];
					
					$array[$key]['icon']=$info['icon'];
					
					$array[$key]['menu']=$item;
				}
			}

		}

		ksort($array);

		return $array;
	}

	public function getApplicationsHelpConfig(){

		$apps = $this->appsInstalled();
		
		if($apps){
		
			foreach ($apps as $app) {

				$config = $this->getAppConfig($app);

				$file= ANYAPP .$app.'/config.php';
		
				if(is_file($file)){

					include $file;
				}
			}
		}
	}

	public function loginLogRecord(){

		$loginTime = [];

		$log = $this->cache->read('login_log');

		if(!$log){

			$loginTime[] = ['login_time'=>$_SERVER['REQUEST_TIME'],'login_city'=>get_city()];

		}else{

			$loginTime = $log;

			$loginTime[] = ['login_time'=>$_SERVER['REQUEST_TIME'],'login_city'=>get_city()];
		}

		$this->cache->write('login_log',$loginTime);

	}

	public function getLoginRecord(){

		return page_array(10,1,$this->cache->read('login_log'),true);
	}
	public function cleanLoginLog(){
		
		$this->cache->deleteCache('login_log');
	}
}