<?php namespace App\Welcome\Components;

use Coffee\Foundation\Component;

class Options extends Component
{
	public function select($select)
	{
		return $this->db("SELECT {$select} FROM options")->rows();
	}

	public function getAllAndExtra()
	{
		return $this->db("SELECT *, (SELECT group_concat(json_object('key',`key`,'value',`value`)) FROM `optionextra` WHERE optionextra.id = options.id) as extra FROM options")->rows();
	}

	public function update($data)
	{
		$config = "";
		foreach ($data as $object) {
			$config .= "($object->id,\"$object->name\",\"$object->alias\",
					\"$object->value\",\"$object->type\",\"$object->description\",\"$object->rules\"),";
		    // 针对多选选项的保存
			if(isset($object->extra)){
				// 先清空关联选项再插入
				$this->db()->exec("DELETE FROM `optionextra` WHERE id = $object->id");
				$extra = "";
				foreach ($object->extra as $item) {
					$extra .="($object->id, \"$item->key\", \"$item->value\"),";
				}
				$extra = rtrim($extra,",");
				$this->db()->exec("INSERT INTO `optionextra` (`id`, `key`, `value`) VALUES {$extra};");
			}
		}
		$config = rtrim($config,",");
		$insert = "INSERT INTO `options` (id,name,alias,value,type,description,rules) VALUES {$config} ON DUPLICATE KEY UPDATE value=VALUES(value);";
		$this->db()->exec($insert);
	}
}