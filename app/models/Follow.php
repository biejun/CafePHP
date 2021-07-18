<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Follows extends Model
{

    public function getFollows($uid)
    {
        return DB::select("SELECT count(fid) FROM follows WHERE `uid`=?", [$uid]);
    }
    
    public function getFollower($fid)
    {
        return DB::select("SELECT count(uid) FROM follows WHERE `fid`=?", [$fid]);
    }
    
    public function follow($uid, $fid)
    {
        $uid = intval($uid);
        $fid = intval($fid);
        if ($uid < 0 || $fid < 0) {
            return false;
        }
        return DB::insert("INSERT INTO follows (`uid`,`fid`,`ctime`) VALUES (?, ?, ?)", [$uid, $fid, date('Y-m-d H:i:s']);
    }
    
    public function unfollow($uid, $fid)
    {
        $followState = $this->getFollowState($uid, $fid);
        if (1 == $followState['following']) {
            return DB::delete("DELETE FROM follows WHERE `uid` = ? AND `fid` = ?", [$uid, $fid]);
        } else {
            return false;
        }
    }
    
    public function getFollowState($uid, $fid)
    {
        $followState = $this->getFollowStateByFids($uid, $fid);
        return $followState[$fid];
    }
    
    public function getFollowStateByFids($uid, $fids)
    {
        if (is_string($fids)) {
            $fids = explode(',', $fids);
        }
        $fids = (array) $fids;
    
        foreach ($fids as $key => $value) {
            $fids[$key] = intval($value);
        }
    
        $_fids = implode(',', $fids);
        $uid = intval($uid);
        
        $followData = DB::select("SELECT * FROM follows WHERE ( uid = '{$uid}' AND fid IN({$_fids}) ) OR ( uid IN({$_fids}) and fid = '{$uid}')");
        $followStates = $this->_formatFollowState($uid, $fids, $followData);
        return $followStates[$uid];
    }
    
    /**
     * 格式化，用户的关注数据.
     *
     * @param int  $uid 用户ID
     * @param array $fids 用户ID数组
     * @param array $followData 关注状态数据
     *
     * @return array 格式化后的用户关注状态数据
     */
    private function _formatFollowState($uid, $fids, $followData)
    {
        !is_array($fids) && $fids = explode(',', $fids);
        foreach ($fids as $fid) {
            $followStates[$uid][$fid] = array(
                    'following' => 0,
                    'follower'  => 0,
            );
        }
        foreach ($followData as $r_v) {
            if ($r_v['uid'] == $uid) {
                $followStates[$r_v['uid']][$r_v['fid']]['following'] = 1;
            } elseif ($r_v['fid'] == $uid) {
                $followStates[$r_v['fid']][$r_v['uid']]['follower'] = 1;
            }
        }
        return $followStates;
    }
}
