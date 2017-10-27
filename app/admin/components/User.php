<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class User extends Component
{
	public function add($user)
	{
		$count = (bool) $this->db->from('users')->select('count(*)')->where('`name`=%s',$user['name'])->one();

		if(!$count){

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
}