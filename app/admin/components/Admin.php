<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Admin extends Component
{
	/* 是否为管理员 */
	public function is($uid)
	{
		return (bool) $this->db->from('usermeta')->select('value')
				->where('`key`=%s adn `uid`=%d','is_admin', $uid)
				->one();
	}
}