<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Api extends Component
{
	public function options()
	{
		return $this->db->from('options')->select('*')->rows();
	}

	public function loginLogs($page, $limit)
	{
		$page = filter_var($page,FILTER_VALIDATE_INT,array("min_range"=>1));
		$limit = filter_var($limit,FILTER_VALIDATE_INT,array("max_range"=>100));
		return $this->load('admin@logs')->getLoginLogs()->result($page, $limit);
	}

	public function todolists($page, $limit)
	{
		$uid = $this->session->get('login_uid');
		$page = filter_var($page,FILTER_VALIDATE_INT,array("min_range"=>1));
		$limit = filter_var($limit,FILTER_VALIDATE_INT,array("max_range"=>100));
		return $this->load('admin@todolists')->get($uid)->result();
	}
}