<?php
class admin_model extends Model{
	# 检查程序是否已安装
	public function check_install($app){
		if(empty($app)) return false;
		return in_array($app,$this->get_app_lists());
	}
	# 执行应用程序数据库语句
	public function query($query){
		if(!empty($query)&&is_array($query)){
			foreach ($query as $sql) {
				$this->db->query($sql);
			}
		}
	}
	# 执行应用程序安装
	public function install($app){
		global $cache;
		$apps = $this->get_app_lists();
		$apps[] = $app;
		$this->db->update("config","config_value='".implode("|",$apps)."'","config_key='apps'");
		$cache->delete_cache('apps');
	}
	# 执行应用程序卸载
	public function uninstall($app){
		global $cache;
		$apps = $this->get_app_lists();
		$apps = array_diff($apps,array($app));
		$this->db->update("config","config_value='".implode("|",$apps)."'","config_key='apps'");
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
}