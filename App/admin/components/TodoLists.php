<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class TodoLists extends Component
{
	public $data = array();

	public function name($uid)
	{
		return "admin:{$uid}:plans";
	}
	public function get($uid)
	{
		$this->data = $this->cache->get($this->name($uid));
		return $this;
	}

	public function add($uid, $planText, $level = 1)
	{
		$data = $this->get($uid)->all();

		$data[] = [
			'text'=>$planText,
			'time' => date('Y-m-d H:i:s'),
			'completed' => 0,
			'level' => $level
		];

		$this->cache->set($this->name($uid),$data);
	}

	public function all()
	{
		if(!empty($this->data)){
			return array_reverse($this->data);
		}
		return array();
	}

	public function result($page = 1, $limit = 10)
	{
		if(!empty($this->data)){
			return array_reverse(array_slice($this->data, $limit * ($page - 1), $limit));
		}
		return array();
	}

	public function delete()
	{
		
	}
}