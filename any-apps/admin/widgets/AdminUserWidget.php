<?php
class AdminUserWidget extends Widget{
	
	public $table = 'user';
	// 是否已登录
	public function isLogin(){

		if(isset($_COOKIE['any_token'])){

			$token=explode(",",secure_core($_COOKIE['any_token']));
			
			if( is_array($token) && isset($token[0]) ){

				$user_id = intval($token[0]);

				if( 0 === $user_id ) return false;
			
				$row = session('login_user');
			
				if(!$row){
			
					$row=$this->db->row($this->table,"user_name,user_password,user_group","user_id='".$user_id."'");
			
					$row['user_id'] = $user_id;
			
					session('login_user',$row);
				}
			
				return $this->checkPassToken($token[2],$row['user_name'],$row['user_password']);
			}
		
		}
		return false;
	}
	// 检查登录令牌是否合法
	public function checkPassToken($token,$user_name,$user_password){

		$user_token = md5($user_name.$user_password);

		if( $token == $user_token ){

			return true;
		}else{
			return false;
		}
	}
	// 是否是网站管理员
	public function isAdmin(){

		if($this->isLogin()){
			// 从session中取出当前登录的用户
			$row = session('login_user');
			// 目前是单用户管理系统
			return ($row['user_group']==3 && $row['user_name'] == $this->config['admin'] ) ? true : false;
		}

		return false;
	
	}
	public function getUserInfo(){

		$row = [];

		if($this->isLogin()){

			$row = session('login_user');
		}
		return $row;
	}
	// 检查登录账号和密码，存在则取出用户ID
	public function checkLogin($user_name,$user_password){

		return $this->db->one($this->table,'user_id',"user_name='$user_name' AND user_password='$user_password' LIMIT 0,1");
	}
	// 检查用户名是否存在
	public function checkUserName($user_name){

		return $this->db->repeat($this->table,'user_name',$user_name);
	}
	// 检查用户密码
	public function checkUserPassword($password,$password_repeat){

		return ($password == $password_repeat) ? true : false;
	}
	// 根据用户ID更新字段
	public function updateUserId($field,$uid){

		$this->db->update($this->table,$field,'user_id='.$uid);
	}
}