<?php
class admin_api extends Model{
	# 获取应用导航菜单
	public function get_app_menu(){
		$apps = $this->get_app_lists();
		$array = array();
		foreach ($apps as $key => $app) {
			$package = ANYAPP .$app.'/package.php';
			if(file_exists($package)){
				$menu = include $package;
				if(isset($menu['menu'])){
					$item=array();
					foreach($menu['menu'] as $k => $v){
						$item[$k]=$v;
					}
					$array[$key]['name']=$menu['name'];
					$array[$key]['icon']=$menu['icon'];
					$array[$key]['menu']=$item;
				}
			}
		}
		return $array;
	}
}