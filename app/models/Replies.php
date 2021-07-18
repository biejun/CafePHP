<?php namespace App\Models;

use HTMLPurifier;
use HTMLPurifier_Config;
use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Replies extends Model
{
	
	public function add($data)
	{
        return DB::table('replies')->insertGetId(
          [
            'uid' => $data['uid'],
            'reply_content' => $data['reply_content'],
            'comment_id' => $data['comment_id'],
            'to_reply_id' => $data['to_reply_id'],
            'to_reply_uid' => $data['to_reply_uid'],
            'reply_time' => date('Y-m-d H:i:s')
          ]
        );
	}
	
	public function delete()
	{
		
	}
    
    public function parseContent($content, $isUpdate = false)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        // 移除空标签
        $config->set('AutoFormat.RemoveEmpty', true);
		$purifier = new HTMLPurifier($config);
        $content = $purifier->purify($content);
        $content = preg_replace_callback("/<a.*?href\s*?=\s*?[\"\']([http|https].*?)[\"\'].*>(.*)<\/a>/is", 
        function($matche) {
            return "<a href=\"$matche[1]\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">$matche[2]</a>";
        }, $content);
    	return $content;
    }
	
	public function list($commentID)
	{
		return DB::select("SELECT a.*,
		  b.name, b.avatar,b.nickname, d.name AS reply_name, d.avatar as reply_avatar, d.nickname as reply_nickname
		  FROM replies AS a
		  LEFT JOIN users AS b ON a.uid = b.uid
		  LEFT JOIN users AS d ON a.to_reply_uid = d.uid
		  WHERE a.comment_id = :id", [ ':id' => $commentID]);
	}
}