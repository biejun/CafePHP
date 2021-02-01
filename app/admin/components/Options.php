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
		$insert = "INSERT INTO ~prefix~options (id,name,alias,value,type,description,rules) VALUES ~replace~ ON DUPLICATE KEY UPDATE value=VALUES(value);";
		$values = "";
		foreach ($data as $object) {
			$values .= "(\"$object->id\",\"$object->name\",\"$object->alias\",
					\"$object->value\",\"$object->type\",\"$object->description\",\"$object->rules\"),";
		}
		$values = rtrim($values,",");
		$this->db($insert, $values)->query();
	}
}