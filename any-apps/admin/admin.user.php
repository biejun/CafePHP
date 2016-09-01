<?php
class admin_user extends Model{
	public $user = array();
	public function is_login(){
		if(isset($_COOKIE['token'])){
			$token=explode(",",UIkit::secure_core($_COOKIE['token']));
			if(isset($token)&&is_array($token)){
				$row=$this->db->row("user","user_name,user_password,user_group","user_id='".$token[0]."'");
				$this->user = $row;
				return ($token[2]===md5($row['user_name'].$row['user_password']))?true:false;
			}
		}else{
			return false;
		}
	}
	public function is_admin(){
		if($this->is_login()){
			return $this->user['user_group']===3?true:false;
		}
	}
	public function is_vip(){
		if($this->is_login()){
			return $this->user['user_group']===2?true:false;
		}
	}
	public function all_user(){

	}
}