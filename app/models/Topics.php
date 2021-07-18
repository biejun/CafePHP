<?php namespace App\Models;

use HTMLPurifier_Config;
use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Topics extends Model
{
	public function add($data)
	{
        return DB::table('topics')->insertGetId(
          [
            'topic_name' => $data['topic_name'],
            'topic_desc' => $data['topic_desc'],
            'topic_cover' => $data['topic_cover']
          ]
        );
	}
    
    public function edit($data)
    {
        return DB::table('topics')->where('topic_id', $data['topic_id'])
          ->update([
              'topic_name' => $data['topic_name'],
              'topic_desc' => $data['topic_desc'],
              'topic_cover' => $data['topic_cover']
          ]);
    }
    
    public function addPostTopic($topicName, $postID)
    {
        $topicName = trim($topicName);
        $topicID = DB::table('topics')->where('topic_name', $topicName)->value('topic_id');
        
        if($topicID) {
            $this->bindPost($topicID, $postID);
        }else{
            $data = [
              'topic_name' => $topicName,
              'topic_desc' => '',
              'topic_cover' => ''
            ];
            $topicID = $this->add($data);
            $this->bindPost($topicID, $postID);
        }
        $this->updateCount('+1', $topicID);
    }
    
    public function bindPost($topicID, $postID) 
    {
        return DB::insert("INSERT INTO post_topic (`topic_id`, `post_id`) VALUES (?, ?)", [$topicID, $postID]);
    }
    
    public function unbindPost($topicID, $postID)
    {
        return DB::delete("DELETE FROM post_topic WHERE `topic_id`=? AND `post_id` = ?", [$topicID, $postID]);
    }
    
    public function updateCount($count, $topicID)
    {
        return DB::update("UPDATE topics SET `topic_count`=topic_count{$count} WHERE `topic_id`=? ", [$topicID]);
    }
	
	public function delete($topicID)
	{
		return DB::delete("DELETE FROM topics WHERE `topic_id` = ?", [$topicID]);
	}
    
    public function recomm()
    {
        return DB::select("SELECT * FROM topics ORDER BY topic_count DESC LIMIT 0, 10");
    }
}