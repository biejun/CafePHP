<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;
use Cafe\Support\Arr;

class Users extends Model
{
    /* 添加一个用户 */
    public function add($user)
    {
        if($this->checkUserName($user['name'])) return false;
        
        /* 取出最新插入的ID */
        $uid = DB::table('users')->insertGetId([
            'name' => $user['name'],
            'nickname' => Arr::get($user, 'nickname', ''),
            'avatar' => Arr::get($user, 'avatar', '@src/img/avatar/default.jpg'),
            'password' => $this->encodePassword($user['password']),
            'created' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('usermeta')->insert([
          ['uid' => $uid, 'key' => 'is_admin', 'value' => Arr::get($user, 'is_admin','false')],
          ['uid' => $uid, 'key' => 'email', 'value' => Arr::get($user, 'email', '') ],
          ['uid' => $uid, 'key' => 'mobile', 'value' => Arr::get($user, 'mobile', '')],
          ['uid' => $uid, 'key' => 'description', 'value' => Arr::get($user, 'description', '')],
          ['uid' => $uid, 'key' => 'ip', 'value' => Arr::get($user, 'ip', '')],
          ['uid' => $uid, 'key' => 'level', 'value' => Arr::get($user, 'level', '1')],
          ['uid' => $uid, 'key' => 'safetycode', 'value' => empty($user['safetycode']) ? '': $this->encodePassword($user['safetycode'])]
        ]);
        
        return $uid;
    }
    
    public function encodePassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function getUserInfoByName($name)
    {
        return DB::selectOne("SELECT uid, name, created, logged FROM users WHERE `name`=?", [$name]);
    }
    
    public function getUserLevel($uid)
    {
    	$userMeta = $this->getMeta($uid);
    	return [
    		'isAdmin' => (bool) $userMeta['is_admin'],
    		'level' => (int) $userMeta['level']
    	];
    }
    
    /* 删除一个用户 */
    public function delete($uid)
    {
        DB::delete("DELETE FROM users WHERE `uid`=?", [$uid]);
    
        DB::delete("DELETE FROM usermeta WHERE `uid`=?", [$uid]);
    }
    
    /* 获取单个用户详细 */
    public function getMeta($uid)
    {
        return DB::selectOne("SELECT a.uid, a.name, a.nickname, a.avatar,
                max(case `b`.`key` when 'is_admin' then `b`.`value` else 'false' end) is_admin,
                max(case `b`.`key` when 'email' then `b`.`value` else '' end) email,
                max(case `b`.`key` when 'mobile' then `b`.`value` else '' end) mobile,
                max(case `b`.`key` when 'description' then `b`.`value` else '' end) description,
                max(case `b`.`key` when 'level' then `b`.`value` else '' end) level
                FROM users as a LEFT JOIN usermeta AS b ON a.uid = b.uid WHERE a.uid=? GROUP BY uid", [$uid]);
    }
    
    /* 检查用户名是否存在 */
    public function checkUserName($name)
    {
        /* 直接转换类型为布尔值，返回是与否就可以了 */
        return (bool) DB::table('users')->where('name', '=', $name)->count();
    }
    
    /*  检查密码是否正确 */
    public function checkPassword($name, $password)
    {
        $pw = DB::table('users')->where('name', '=', $name)->value('password');
        return password_verify($password, $pw);
    }
    
    /* 更新登录令牌 */
    public function updateToken($name, $days = 1)
    {
        $timeout = date('Y-m-d H:i:s', strtotime("+{$days} day")); // 登录状态记录一天
        $data = [
            'logged' => date('Y-m-d H:i:s'),
            'timeout' => $timeout,
            'token' => md5(HASH . md5($name) . $timeout)
        ];
    
        DB::update("UPDATE users SET `logged`=?,`timeout`=?,`token`=? WHERE `name`=?", [
            $data['logged'],
            $data['timeout'],
            $data['token'],
            $name
        ]);
        
        return $data;
    }
    
    /* 检查登录令牌 */
    public function checkToken($loginToken)
    {
        $currentTime = date('Y-m-d H:i:s');
        $verifications = DB::selectOne("SELECT uid, name, timeout FROM users WHERE `token`=?", [ $loginToken ]);
        if ($verifications && $currentTime < $verifications->timeout) {
            return $verifications;
        }
        return false;
    }
    
    /* 获取用户数据 */
    public function getData($page=1, $limit=10, $order = 'created DESC')
    {
        return DB::select("SELECT uid, name, nickname, avatar, created, logged FROM users ORDER BY {$order} LIMIT ".(($page-1)*$limit).",".$limit);
    }
    
    /* 获取用户详细数据 */
    public function getDataWithDetail($page=1, $limit=10, $order = 'created DESC')
    {
        return DB::select("SELECT a.uid, a.name, a.nickname, a.created, a.logged,
                max(case `b`.`key` when 'is_admin' then `b`.`value` else 'false' end) is_admin,
                max(case `b`.`key` when 'email' then `b`.`value` else '' end) email,
                max(case `b`.`key` when 'description' then `b`.`value` else '' end) description,
                max(case `b`.`key` when 'level' then `b`.`value` else '' end) level
            FROM users AS a 
            LEFT JOIN usermeta AS b 
            ON a.uid = b.uid 
            GROUP BY a.uid
            ORDER BY {$order} LIMIT ".(($page-1)*$limit).",".$limit);
    }
    
    public function search($name)
    {
        return DB::select("SELECT uid, name, nickname, avatar FROM users WHERE name like ? ", ['%'.$name.'%']);
    }
}