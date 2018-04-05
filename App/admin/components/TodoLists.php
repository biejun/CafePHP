<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class TodoLists extends Component
{
	public $data = array();

	/* 待办记录ID [组件所在应用]:[用户ID]:[组件名] */
	public function id($uid)
	{
		return "admin:{$uid}:todolist";
	}

	/* 获取数据 */
	public function getData($uid)
	{
		$this->data = $this->getBind('data')->get($this->id($uid));
		return $this;
	}

	/* 添加待办 */
	public function add($uid, $todo, $level = 1)
	{
		$data = $this->getData($uid)->all();

		$data[] = [
			'text'=>$text,
			'time' => date('Y-m-d H:i:s'),
			'completed' => 0,
			'level' => $level
		];

		$this->getBind('data')->set($this->name($uid),$data);
	}

	/* 获取全部数据 */
	public function all()
	{
		if(!empty($this->data)){
			return array_reverse($this->data);
		}
		return array();
	}

	/* 数据分页 */
	public function page($page = 1, $limit = 10)
	{
		if(!empty($this->data)){
			return array_reverse(array_slice($this->data, $limit * ($page - 1), $limit));
		}
		return array();
	}

	/* 已完成 */
	public function completed($uid, $todo = [])
	{
		$data = $this->getData($uid)->all();
		foreach ($data as &$value) {
			if($value['time'] === $todo['time']) {
				$value['completed'] = 1;
				break;
			}
		}
		$this->getBind('data')->set($this->name($uid),$data);
	}

	/* 删除待办 */
	public function delete($uid, $todo = [])
	{
		$data = $this->getData($uid)->all();
		$data = array_filter($data, function($v) use ($todo) {
			return ($v !== $todo);
		});
		$this->getBind('data')->set($this->name($uid),$data);
	}
}