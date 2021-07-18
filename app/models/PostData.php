<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class PostData extends Model
{
	public function add($postID, $data, $dataType = 0)
    {
        return DB::insert("INSERT INTO post_data (
          `uid`,
          `post_id`,
          `data`,
          `data_type`
        ) VALUES (?, ?, ?, ?)", [model('Account')->uid(), $postID, $data, $dataType]);
    }
    
    public function get($postID, $dataType = null)
    {
        $query = "SELECT * FROM post_data WHERE `post_id`= $postID";
        if(!is_null($dataType)) {
            $query .= " AND `data_type` = $dataType";
        }
        return DB::select($query);
    }
}