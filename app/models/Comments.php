<?php namespace App\Models;

use HTMLPurifier;
use HTMLPurifier_Config;
use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Comments extends Model
{	
	public function add($data)
	{
        return DB::table('comments')->insertGetId(
          [
            'uid' => $data['uid'],
            'comment_content' => $data['comment_content'],
            'post_id' => $data['post_id'],
            'comment_time' => date('Y-m-d H:i:s')
          ]
        );
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
	
	public function digg()
	{
		
	}
	
	public function delete()
	{
		
	}
    
    public function pager($postID)
    {
        $comments = $this->list($postID);
        foreach($comments as $row) {
            $replies = model('Replies')->list($row->comment_id);
            if(count($replies) > 0) {
                $row->replies = $replies;
            }
        }
        return $comments;
    }
	
	public function list($postID)
	{
		return DB::select("SELECT a.*, b.name, b.nickname, b.avatar
         FROM comments AS a 
         LEFT JOIN users AS b ON a.uid = b.uid 
         WHERE post_id = :id", [':id' => $postID]);
	}
	
	private function parseAt()
	{
		
	}
}