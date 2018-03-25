<?php namespace App\Admin\Components;
/**
 * 我们把权限操作少的代码集中在这个API文件里，
 * 前端通过请求 /admin/api/函数名即可获取数据，
 * 不建议你将具有update、delete等危险操作的接口定义在这里，
 * 如有需要请定义在route.php文件中，以便更好的控制访问权限。
 */
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

	public function users($page, $limit)
	{
		$page = filter_var($page,FILTER_VALIDATE_INT,array("min_range"=>1));
		$limit = filter_var($limit,FILTER_VALIDATE_INT,array("max_range"=>100));
		return $this->load('admin@users')->result($page, $limit);
	}

	public function operatelogs($page, $limit)
	{
		$page = filter_var($page,FILTER_VALIDATE_INT,array("min_range"=>1));
		$limit = filter_var($limit,FILTER_VALIDATE_INT,array("max_range"=>100));
		return $this->load('admin@logs')->getOperateLogs()->result($page, $limit);	
	}
}