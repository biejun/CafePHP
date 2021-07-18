<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class AdminOptions extends Model
{
    public function select($select)
    {
        return DB::table('options')->select("{$select}")->get();
    }

    public function getAllAndExtra()
    {
        return DB::select("SELECT *, (SELECT group_concat(json_object('key',`key`,'value',`value`)) FROM `optionextra` WHERE optionextra.id = options.id) as extra FROM options");
    }

    public function update($data)
    {
        $config = "";
		$extra = "";
        foreach ($data as $object) {
            $config .= "($object->id,\"$object->name\",\"$object->alias\",
					\"$object->value\",\"$object->type\",\"$object->description\",\"$object->rules\"),";
            // 针对多选选项的保存
            if (isset($object->extra)) {
				$extra_array = $object->extra;
                foreach ($extra_array as $item) {
                    $extra .="($object->id, \"$item->key\", \"$item->value\"),";
                }
            }
        }
        $config = rtrim($config, ",");
        $insert = "INSERT INTO `options` (id,name,alias,value,type,description,rules) VALUES {$config} ON DUPLICATE KEY UPDATE value=VALUES(value);";
        
        $pdo = DB::connection()->getPdo();
        $pdo->exec($insert);
		if( !empty($extra) ) {
			// 先清空关联选项再插入
			$pdo->exec("TRUNCATE TABLE `optionextra`;");
			$extra = rtrim($extra, ",");
			$pdo->exec("INSERT INTO `optionextra` (`id`, `key`, `value`) VALUES {$extra};");
		}
    }
}
