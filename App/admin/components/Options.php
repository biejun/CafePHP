<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Options extends Component
{
	public function get()
	{
		return $this->db->from('options')->select('name,value')->rows();
	}

	public function updateAll($data)
	{
		$query = "INSERT INTO #table#options(id,name,alias,value,type,description,rules) VALUES";
		foreach ($data as $object) {
			$query .= "(\"$object->id\",\"$object->name\",\"$object->alias\",
					\"$object->value\",\"$object->type\",\"$object->description\",\"$object->rules\"),";
		}
		$query = str_replace('#table#', G('database','prefix'),
			rtrim($query,",")." ON DUPLICATE KEY UPDATE value=VALUES(value)");
		$this->db->query($query);
	}
}