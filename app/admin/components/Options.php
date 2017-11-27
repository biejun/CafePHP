<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Options extends Component
{
	public function get()
	{
		return $this->db->from('options')->select('name,value')->rows();
	}

	public function update()
	{

	}
}