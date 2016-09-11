<?php
if(!defined('ABSPATH'))exit('Access denied!');

class account extends UI{

	# 过滤用户名
	private $disabled = array(
		'u',
		'admin',
		'fuck',
		'123456',
		'111'
	);
	public function index(){
		$this->render('index');
	}
	public function test(){
		$arr = array('t'=>'bbb');
		$this->json($arr);
	}
	public function login(){
		if( $this->is_login )
			header( "Location:".PATH );

		session('csrf_id',APP_VALIDATE);
		$this->assign('csrf_id',APP_VALIDATE);
		$this->render('login');
	}
	public function register(){
		if( $this->is_login )
			header( "Location:".PATH );

		session('csrf_id',APP_VALIDATE);
		$this->assign('csrf_id',APP_VALIDATE);
		$this->render('register');
	}
	public function forget(){
		if( $this->is_login )
			header( "Location:".PATH );
	}
	public function qq(){

	}
	public function post_login_request(){
		$data = query_vars(array('user_name','user_password','csrf_id'));
		if($data){
			if(empty($data['csrf_id'])){
				$this->message('error','缺少参数，请求失败!');
			}else{
				if(session('csrf_id')!=trim($data['csrf_id'])){
					$this->message('error','参数错误，请求失败!');
				}
			}
			if(empty($data['user_name'])){
				$this->message('error','用户名不能为空!');
			}
			if(!model('account')->check_user_name($data['user_name'])){
				$this->message('error','用户名不存在!');
			}
			if(!isset($data['user_password']{5})){
				$this->message('error','密码不能少于六位!');
			}
			$data['user_password'] = md5($data['user_password'] . VALIDATE);
			$uid = model('account')->check_login($data['user_name'],$data['user_password']);
			if($uid){
				$user_login_time = APP_TIME;
				model('account')->update_user_id('user_login_time=$user_login_time',$uid);
				$token=$uid.','.$user_login_time.','.md5($data['user_name'].$data['user_password']);
				setcookie("token",secure_core($token,'ENCODE'),time()+3600*24*365,PATH);
				$this->message('success','登录成功!');
			}else{
				$this->message('error','您的用户名或密码不正确!');
			}
		}
	}
	public function post_register_request(){
		$data = query_vars(array('user_name','user_password','user_password_repeat','csrf_id'));
		if($data){
			if(empty($data['csrf_id'])){
				$this->message('error','缺少参数，请求失败!');
			}else{
				if(session('csrf_id')!=trim($data['csrf_id'])){
					$this->message('error','参数错误，请求失败!');
				}
			}
			if(empty($data['user_name'])){
				$this->message('error','用户名不能为空!');
			}
			if(in_array($data['user_name'],$this->disabled)){
				$this->message('error','用户名已被注册!');
			}
			if(!preg_match('/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9、。&]+$/u', $data['user_name'])){
				$this->message('error','用户名不能包含特殊字符!');
			}
			if(model('account')->check_user_name($data['user_name'])){
				$this->message('error','用户名已被注册!');
			}
			if(!isset($data['user_password']{5})){
				$this->message('error','密码过于简单!');
			}
			if(!model('account')->
				check_user_password($data['user_password'],$data['user_password_repeat'])){
				$this->message('error','两次输入的密码不一致!');
			}
			$array = array();
			$array['user_name'] = $data['user_name'];
			$array['user_password'] = md5($data['user_password'] . VALIDATE);

			$uid = model()->insert_table('user',$array);
			if($uid){
				$user_login_time = APP_TIME;
				$token=$uid.','.$user_login_time.','.md5($array['user_name'].$array['user_password']);
				setcookie("token",secure_core($token,'ENCODE'),time()+3600*24*365,PATH);
				$this->message('success','注册成功!');
			}else{
				$this->message('error','注册失败!');
			}
		}
	}
	public function post_admin_config(){
		
	}
}