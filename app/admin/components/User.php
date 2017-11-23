<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class User extends Component
{
	public function add($user)
	{
		if(!$this->checkUsername($user['name'])){

			$this->db->from('users')->insert([
				'name'=>trim($user['name'])
				,'password'=>password_hash($user['password'],PASSWORD_BCRYPT)
				,'created' => date('Y-m-d H:i:s')
			]);

			$uid = $this->db->id();

			$this->db->from('usermeta')->multi_insert(array('uid','key','value')
				,array(
					array($uid,'is_admin', isset($user['is_admin']) ? $user['is_admin'] : 'false')
					,array($uid,'email', isset($user['email']) ? $user['email'] : '')
					,array($uid,'avatar', isset($user['avatar']) ? $user['avatar'] : '')
					,array($uid,'description', isset($user['description']) ? $user['description'] : '')
					,array($uid,'level', isset($user['level']) ? $user['level'] : '1')
					,array($uid,'safetycode', isset($user['safetycode']) ? password_hash($user['safetycode'],PASSWORD_BCRYPT) : '')
				)
			);
		}
	}

	public function delete($uid)
	{

	}

	public function update()
	{

	}

	public function checkUsername($username)
	{
		return (bool) $this->db->from('users')->select('count(*)')->where('`name`=%s',$username)->one();
	}

	public function checkPassword($username, $password)
	{
		$pw = $this->db->from('users')->select('password')->where('`name`=%s',$username)->one();
		return password_verify($password, $pw);
	}

	public function updateToken($username)
	{
		$dbhash = G('database','hash');
		$time = $_SERVER['REQUEST_TIME'];
		$timeout = $time + 3600 * 24; // 登录状态记录一天
		$data = [
			'logged' => $time,
			'timeout' => $timeout,
			'token' => md5( $dbhash . md5($username) . $timeout )
		];
		$this->db->from('users')->where('`name` = %s',$username)->update($data);
		return $data;
	}
}