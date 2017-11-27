<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Api extends Component
{
	public function options()
	{
		return $this->db->from('options')->select('*')->rows();
	}
}