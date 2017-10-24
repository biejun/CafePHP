<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class User extends Component
{
	public function add($username,$password,$group)
	{
		$count = $this->db->from('users')->select('count(*)')->where('`name`=%s',$username)->one();

		if($count === '0'){
			
			$this->db->from('users')->insert([
				'name'=>trim($username)
				,'password'=>password_hash($password,PASSWORD_BCRYPT)
				,'created' => $_SERVER['REQUEST_TIME']
				,'group'=> $group
			]);
			
			$uid = $this->db->id();

			$this->db->from('user_info')->insert(['uid' => $uid]);
		}
	}

	public function delete($uid)
	{

	}

	public function update()
	{

	}
}