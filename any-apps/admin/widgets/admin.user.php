<?php
class admin_user_widget extends Widget{
	public $table = 'user';
	public function is_login(){
		if(isset($_COOKIE['token'])){
			$token=explode(",",secure_core($_COOKIE['token']));
			if(isset($token)&&is_array($token)){
				$row = session('login_user');
				if(!$row){
					$row=$this->db->row($this->table,"user_name,user_password,user_group","user_id='".$token[0]."'");
					$row['user_id'] = $token[0];
					session('login_user',$row);
				}
				return ($token[2]===md5($row['user_name'].$row['user_password']))?true:false;
			}
		}else{
			return false;
		}
	}
	public function is_admin(){
		if($this->is_login()){
			$row = session('login_user');
			return $row['user_group']==3?true:false;
		}
	}
	public function check_login($user_name,$user_password){
		return $this->db->one($this->table,'user_id',"user_name='$user_name' AND user_password='$user_password' LIMIT 0,1");
	}
	public function check_user_name($user_name){
		return $this->db->repeat($this->table,'user_name',$user_name);
	}
	public function check_user_password($password,$password_repeat){
		return ($password == $password_repeat) ? true : false;
	}
	public function update_user_id($field,$uid){
		$this->db->update($this->table,$field,'user_id=$uid');
	}
}