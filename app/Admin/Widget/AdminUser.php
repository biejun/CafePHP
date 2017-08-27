<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminUser extends Widget
	{

		public $table = 'users';

		public function checkUserName($username)
		{

			$username = $this->db->escape($username);

			return $this->db->repeat($this->table,"name","`name` ='$username' AND `group` = '3' LIMIT 0,1");
		}

		public function checkPassword($username,$password)
		{

			$username = $this->db->escape($username);

			$hash = $this->db->one($this->table,"password","`name` = '$username'");

			return password_verify($password, $hash);
		}

		public function isAdmin()
		{
			if( isset($_COOKIE['__admin_token__']) ){

				if( !__session('__admin_name__') ){

					$token = $this->db->escape(__getcookie('__admin_token__'));

					$time = $_SERVER['REQUEST_TIME'];

					$data = $this->db->row($this->table,"name,timeout","`token`='$token' and `group` = '3'");

					if( $data && $time < $data['timeout'] ){
						// 将验证结果记录到临时会话。只验证一次token，减少SQL查询
						__session('__admin_name__',$data['name']);
						return true;
					}
				}else{
					return true;
				}
			}
			return false;
		}

		public function updateLoginTime($username)
		{
			$salt = conf('system','salt');

			$time = $_SERVER['REQUEST_TIME'];

			$timeout = $time + 3600 * 24; // 登录状态记录一天

			$token = md5( $salt . md5($username) . $timeout );

			$this->db->update($this->table,[
				'timeout' => $timeout,
				'logged' => $time,
				'token' => $token,
			],"`name` = '$username' and `group` = '3'");

			__setcookie( '__admin_token__', $token , $timeout);

			widget('admin@log')->setLog($username, $time);
		}

		public function updatePassword($oldPassword,$newPassword)
		{
			$adminToken = __getcookie('__admin_token__');
			$username = __session('__admin_name__');

			if($this->checkPassword($username,$oldPassword)){

				$newPassword = $this->db->escape($newPassword);

				$this->db->update($this->table,[
					'password' => password_hash($newPassword,PASSWORD_BCRYPT)
				],"`token` = '$adminToken' and `group` = '3'");

				return true;
			}
			return false;
		}
	}
}