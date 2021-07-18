<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Notifications extends Model
{
	public function add()
	{
		
	}
	
	public function update()
	{
		
	}
    
    public function addPostAt($uid, $atUid, $postID)
    {
        // no_type 值为5 表示是在内容中at到了此用户
        DB::insert("INSERT INTO notifications (
        `uid`, `atuid`, `no_type`, `type_id`) 
        VALUES (?, ?, 5, ?)", [$uid, $atUid, $postID]);
    }
    
    public function delPostAt($uid, $atUid, $postID)
    {
        // no_type 值为5 表示是在内容中at到了此用户
        return DB::delete("DELETE FORM notifications 
        WHERE `uid` =? AND `atuid` IN (?) AND `no_type`=5 AND `type_id`=?", [$uid, $atUid, $postID]);
    }
	
	public function delete()
	{
		
	}
}