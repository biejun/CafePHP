<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Options extends Component
{
	public function select($select)
	{
		return $this->db("SELECT {$select} FROM ~prefix~options")->query()->rows();
	}

	public function all()
	{
		return $this->select("*");
	}

	public function update($data)
	{
		$query = "INSERT INTO ~prefix~options (id,name,alias,value,type,description,rules) VALUES";
		foreach ($data as $object) {
			$query .= "(\"$object->id\",\"$object->name\",\"$object->alias\",
					\"$object->value\",\"$object->type\",\"$object->description\",\"$object->rules\"),";
		}
		$query = rtrim($query,",")." ON DUPLICATE KEY UPDATE value=VALUES(value)";
		$this->db($query)->query();
	}
}