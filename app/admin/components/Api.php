<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Api extends Component
{
	public function settings()
	{
		$res = $this->db->from('settings')->select('*')->rows();
		return $res;
	}
}