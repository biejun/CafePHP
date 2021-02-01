<?php namespace App\Welcome\Components;

use Cafe\Foundation\Component;

class Users extends Component
{
    /* 添加一个用户 */
    public function add($user)
    {
        if(!$this->checkUsername($user['name'])) {

            $this->db("INSERT INTO users (`name`,`password`,`created`) VALUES (?,?,?)"
                ,$user['name']
                ,password_hash($user['password'],PASSWORD_BCRYPT)
                ,date('Y-m-d H:i:s')
            );
            /* 取出最新插入的ID */
            $uid = $this->db()->id();
            // 输出结果 (`uid`,`key`,`value`) VALUES (1,'is_admin','false'),(1,'email','12345@demo.com')
            $meta = $this->db()->insertRows(['uid','key','value']
                ,[
                    [$uid,'is_admin', isset($user['is_admin']) ? $user['is_admin'] : 'false']
                    ,[$uid,'email', isset($user['email']) ? $user['email'] : '']
                    ,[$uid,'avatar', isset($user['avatar']) ? $user['avatar'] : '']
                    ,[$uid,'description', isset($user['description']) ? $user['description'] : '']
                    ,[$uid,'level', isset($user['level']) ? $user['level'] : '1']
                    ,[$uid,'safetycode', isset($user['safetycode']) 
                        ? password_hash($user['safetycode'],PASSWORD_BCRYPT)
                        : '']
                ]
            );

            $this->db("INSERT INTO usermeta {$meta}");
        }
    }

    /* 获取单个用户详细 */
    public function getMeta($uid)
    {
        return $this->db("SELECT max(case `key` when 'is_admin' then `value` else 'false' end) is_admin,
                max(case `key` when 'email' then `value` else '' end) email,
                max(case `key` when 'avatar' then `value` else '' end) avatar,
                max(case `key` when 'description' then `value` else '' end) description,
                max(case `key` when 'level' then `value` else '' end) level,
                max(case `key` when 'safetycode' then `value` else '' end) safetycode 
                FROM usermeta WHERE `uid`=? GROUP BY uid", $uid)
                ->row();
    }

    /* 删除一个用户 */
    public function delete($uid)
    {
        $this->db("DELETE FROM users WHERE `id`=?", $uid);

        $this->db("DELETE FROM usermeta WHERE `uid`=?", $uid);
    }

    /* 检查用户名是否存在 */
    public function checkUsername($username)
    {
        /* 直接转换类型为布尔值，返回是与否就可以了 */
        return (bool) $this->db("SELECT count(*) FROM users WHERE `name`=?", $username)->one();
    }

    /*  检查密码是否正确 */
    public function checkPassword($username, $password)
    {
        $pw = $this->db("SELECT password FROM users WHERE `name`=?", $username)->one();
        return password_verify($password, $pw);
    }

    /* 更新登录令牌 */
    public function updateToken($username, $days = 1)
    {
        $timeout = date('Y-m-d H:i:s',strtotime("+{$days} day")); // 登录状态记录一天
        $data = [
            'logged' => date('Y-m-d H:i:s'),
            'timeout' => $timeout,
            'token' => md5( HASH . md5($username) . $timeout )
        ];

        $this->db("UPDATE users SET `logged`=?,`timeout`=?,`token`=? WHERE `name`=?"
            ,$data['logged']
            ,$data['timeout']
            ,$data['token']
            ,$username
        );
        
        return $data;
    }

    /* 检查登录令牌 */
    public function checkToken($loginToken)
    {
        $currentTime = date('Y-m-d H:i:s');
        $verifications = $this->db("SELECT id,name,timeout FROM users WHERE `token`=?",
            $loginToken)->row();
        if($verifications && $currentTime < $verifications['timeout']){
            return $verifications;
        }
        return false;
    }

    /* 获取用户数据 */
    public function getData($page=1, $limit=10, $order = 'created DESC')
    {
        return $this->db("SELECT id,name,created,logged FROM users ORDER BY {$order} LIMIT ".(($page-1)*$limit).",".$limit)->rows();
    }

    /* 获取用户详细数据 */
    public function getDetailData($page=1, $limit=10, $order = 'created DESC')
    {
        return $this->db("SELECT a.id,a.name,a.created,a.logged,
                max(case `key` when 'is_admin' then `value` else 'false' end) is_admin,
                max(case `key` when 'email' then `value` else '' end) email,
                max(case `key` when 'avatar' then `value` else '' end) avatar,
                max(case `key` when 'description' then `value` else '' end) description,
                max(case `key` when 'level' then `value` else '' end) level,
                max(case `key` when 'safetycode' then `value` else '' end) safetycode 
            FROM users AS a 
            LEFT JOIN usermeta AS b 
            ON a.id = b.uid 
            GROUP BY a.id
            ORDER BY {$order} LIMIT ".(($page-1)*$limit).",".$limit)->rows();
    }
}