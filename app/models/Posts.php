<?php namespace App\Models;

use HTMLPurifier;
use HTMLPurifier_Config;
use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;
use Cafe\Support\Str;

class Posts extends Model
{	
	public function add($data)
	{
        // 需要测试在DB::insert之后 DB::getPdo()->lastInsertId();
        return DB::table('posts')->insertGetId(
          [
            'uid' => $data['uid'],
            'post_title' => $data['post_title'],
            'post_time' => $data['post_time'],
            'post_lock' => $data['post_lock'],
            'post_content' => $data['post_content'],
            'post_type' => $data['post_type'],
            'post_privacy' => $data['post_privacy'],
            'post_pass' => $data['post_pass'],
            'post_url' => $this->postUrl()
          ]
        );
	}
	
	public function update($data)
	{
		$updateSql = "UPDATE posts SET 
		  `post_title` = ?, 
		  `post_update` = ?,
		  `post_lock` = ?,
		  `post_content` = ?,
		  `post_type` = ?,
		  `post_privacy` = ?,
		  `post_pass` = ? WHERE `post_id` = ? ";
		return DB::update($updateSql, [
            $data['post_title'],
            $data['post_update'],
            $data['post_lock'],
            $data['post_content'],
            $data['post_type'],
            $data['post_privacy'],
            $data['post_pass'],
            $data['post_id']
        ]);
	}
	
	public function delete($postID)
	{
		return DB::delete("DELETE FORM post WHERE `post_id` = ?", [$postID]);
	}
	
	public function read($uid, $postUrl)
	{
		return DB::selectOne("SELECT post_id, post_title, post_content, post_type,
            post_lock, date_format(post_time, '%Y年%m月%d日') as post_time, post_url, digg_count, read_count, comment_count,
            b.name, b.avatar, b.nickname 
            FROM posts AS a 
            LEFT JOIN users AS b ON a.uid = b.uid 
            WHERE `a`.`uid`=? AND `post_url`= ?", [ $uid, $postUrl ]);
	}
    
    public function readByID($postID)
    {
    	return DB::selectOne("SELECT post_id, post_title, post_content
        ,post_type, post_lock,post_privacy FROM posts WHERE `post_id`= ?", [$postID]);
    }
    
    public function count($uid)
    {
        // SELECT COUNT(`post_id`) FROM posts WHERE `uid`=?
        return DB::table('posts')->where('uid', $uid)->count();
    }
    
    public function list($options = [])
    {
        $rows = DB::select("SELECT post_id, post_title, post_content,
         post_type, post_lock, a.post_time, post_url, digg_count, read_count, comment_count,
         b.name, b.avatar, b.nickname 
         FROM posts AS a 
         LEFT JOIN users AS b ON a.uid = b.uid 
         WHERE `post_privacy` = 0");
         
        if(count($rows) > 0) {
            foreach($rows as $row) {
                $row->post_desc = $this->truncateContent($row->post_content, $row->post_type);
                $row->post_time = formatTime(strtotime($row->post_time));
                $row->post_url = $this->makeUrl($row->name, $row->post_url);
                $row->user_url = u($row->name);
                $row->avatar = u($row->avatar);
                // 获取关联数据（附件： 如图片、音乐、视频）
                $images = model('PostData')->get($row->post_id , 1);
                $imageCount = count($images);
                if($imageCount > 0) {
                    $row->post_image_thumbs = $images;
                    $row->post_image_count = $imageCount;
                }
            }
        }
        return $rows;
    }
	
	public function digg($uid, $postID)
	{
		if($this->existDigg($uid, $postID)) {
			DB::delete("DELETE FROM post_digg WHERE `uid`=? AND `post_id`=?", [$uid, $postID]);
			return 0;
		}else{
			DB::insert("INSERT INTO post_digg (`uid`, `post_id`) VALUES (?, ?)", [$uid, $postID]);
			return 1;
		}
	}
	
	public function existDigg($uid, $postID) {
		return DB::table('post_digg')->where([
            ['uid', $uid],
            ['post_id', $postID]
        ])->exists();
	}
	
	public function parseContent($content, $isUpdate = false)
	{
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Trusted', true);
        $config->set('HTML.ForbiddenElements', ['script', 'noscript', 'style', 'textarea']);
        // $config->set('HTML.DefinitionID', 'enduser-customize.html tutorial');
        // $config->set('HTML.DefinitionRev', 1);
        $config->set('Core.Encoding', 'UTF-8');
        //$config->set('HTML.AllowedAttributes', 'div, *[style|class], img.src, input[type|checked]');
        // 移除空标签
        $config->set('AutoFormat.RemoveEmpty', true);
        $def = &$config->getHTMLDefinition(true); // reference to raw
        $def->addAttribute('input', 'type', 'Enum#checkbox');
        // if ($def = $config->maybeGetRawHTMLDefinition()) {
        //     $def->addElement('input', 'Inline', 'Inline', 'Common', ['checked' => 'Bool', 'type' => 'Enum#checkbox']);
        // }
		$purifier = new HTMLPurifier($config);
        $content = $purifier->purify($content);
        $content = preg_replace_callback("/<a.*?href\s*?=\s*?[\"\']([http|https].*?)[\"\'].*>(.*)<\/a>/is", 
        function($matche) {
            return "<a href=\"$matche[1]\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">$matche[2]</a>";
        }, $content);
		return $content;
	}
    
    // 图片从临时目录移动到正式目录，并写入数据库 （本地存储）
    public function imageMove($images = '', $postID = 0, $postContent = '')
    {
        if( empty($images) || $postID === 0 ) return;
        
        $images = explode(',', $images);
        foreach($images as $_tmpImgPath) {
            // 文件物理路径
            $fromPath = app()->publicPath($_tmpImgPath);
            $toPath = str_replace('_tmp/', '', $fromPath);
            
            $toPathInfo = pathinfo($toPath);
            
            if(isset($toPathInfo['dirname']) && file_exists($fromPath)) {
                
                @mkdir($toPathInfo['dirname'] . '/', 0777);
                @copy($fromPath, $toPath);
                // 从临时目录删除
                @unlink($fromPath);
                // 文件相对路径
                $imgPath = str_replace('_tmp/', '', $_tmpImgPath);
                // 替换内容中的路径
                $postContent = str_replace($_tmpImgPath, $imgPath, $postContent);
                
                model('PostData')->add($postID, $imgPath);
                
                $filename = $toPathInfo['filename'];
                $ext = $toPathInfo['extension'];
                $basename = $toPathInfo['basename'];
                $thumbFilename = $filename . "_thumb." . $ext;
                $thumbImgPath = str_replace($basename, $thumbFilename, $imgPath);
                
                $thumbPath = $toPathInfo['dirname'] . "/" .$thumbFilename;
                \Cafe\Image\Image::thumb($toPath, $thumbPath, 320);
                
                model('PostData')->add($postID, $thumbImgPath, 1);
            }
        }
        
        DB::update("UPDATE posts SET `post_content` = ? WHERE `post_id`= ? ", [$postContent, $postID]);
    }
    
    private function truncateContent($content, $type)
    {
        if($type == 3) return truncate(strip_tags($content), 100);
        $content = strip_tags($content, '<img><ul><li><p><input><strike>');
        /* 
           "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
         */
        $content = preg_replace_callback("/(<img.+src=\"?(.+\.(jpg|jpeg|gif|png))\".*?[\/]?>)/", 
        function($matches) {
            $src = $matches[2];
            //\print_r($matches);
            if(Str::contains($src, '@src/img/emoji')) {
                return $matches[0];
            }
        	return '';
        }, $content);
        return $content;
    }
    
    public function makeUrl($userName, $url)
    {
        return u($userName. '/post/'. $url);
    }
    
    public function postUrl()
    {
        return Str::random(12);
    }
}