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
	# 检查应用程序列表
	public function check_apps(){
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
					$meta['install']=$this->check_install($info['app']);
					$array[] = $meta;
				}
			}
		}
		return json_encode($array);
	}
}