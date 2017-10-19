<?php
namespace App\Post\Components;

use Coffee\Foundation\Component;

class Api extends Component
{
	//public $table = 'configs';

	public function hello()
	{
		$t = $this->db->from('configs')->select('name,alias')->where('`name`=%s and `group`=%d','theme',1)->rows();
		print_r($t);
	}
}