<?php
class admin_widget extends Widget{

	# 检查程序是否已安装
	public function check_install($app){
		if(empty($app)) return false;
		return in_array($app,self::$_installed);
	}
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
	# 执行应用程序安装
	public function install($app,$query){
		global $cache;
		$this->query($query);
		$apps = self::$_installed;
		$apps[] = $app;
		$this->db->update($this->table,"config_value='".implode("|",$apps)."'","config_key='apps'");
		$this->db->insert($this->table,array('config_key'=>$app));
		$cache->delete_cache($app.'_config');
		$cache->delete_cache('apps');
	}
	# 执行应用程序卸载
	public function uninstall($app,$query){
		global $cache;
		$this->query($query);
		$apps = self::$_installed;
		$apps = array_diff($apps,array($app));
		$this->db->update($this->table,"config_value='".implode("|",$apps)."'","config_key='apps'");
		$this->db->delete($this->table,"config_key='$app'");
		$cache->delete_cache($app.'_config');
		$cache->delete_cache('apps');
	}
	# 获取应用程序状态
	public function get_app_status($app){
		$array = array();
		$path = PATH.'admin/';
		if($this->check_install($app)){
			$array['status'] = '卸载';
			$array['path'] = $path.'uninstall.html?app_name='.$app;
		}else{
			if(is_file(ANYAPP.$app.'/update.php')){
				$array['status'] = '更新';
				$array['path'] = $path.'update.html?app_name='.$app;
			}else{
				$array['status'] = '安装';
				$array['path'] = $path.'install.html?app_name='.$app;
			}
		}
		return $array;
	}
	# 获取应用程序列表
	public function get_system_apps(){
		global $cache;
		$packages = $cache->read('packages');
		$array=array();
		if(!empty($packages)){
			foreach ($packages as $info) {
				if(!empty($info)){
					$meta = array();
					$meta['app'] = $info['app'];
					$meta['name'] = $info['name'];
					$meta['description'] = $info['description'];
					$meta['version'] = $info['version'];
					$meta['author'] = $info['author'];
					$meta['date'] = $info['date'];
					$meta['special'] = $info['special'];
					$meta['install']=$this->get_app_status($info['app']);
					$array[] = $meta;
				}
			}
		}
		return $array;
	}
	public function get_app_menu(){
		global $cache;
		$packages = $cache->read('packages');
		$array = array();
		foreach ($packages as $info) {
			foreach (self::$_installed as $key => $app) {
				if( $info['app']==$app && isset($info['options'])){
					$item=array();
					foreach($info['options'] as $id => $v){
						list($title,$url) = $v;
						if(!empty($url)) $url = PATH.'admin/options.html?app='.$app.'&admin='.$url;
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
}