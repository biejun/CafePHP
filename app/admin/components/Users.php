<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Users extends Component
{

	/* 添加一个用户 */
	public function add($user)
	{
		if(!$this->checkUsername($user['name'])){

			$this->exec( "INSERT INTO ~prefix~users (`name`,`password`,`created`) VALUES (%s,%s,%s)"
				,$user['name']
				,password_hash($user['password'],PASSWORD_BCRYPT)
				,date('Y-m-d H:i:s') )->query();

			$uid = $this->db->id();
			// $this->db->from('users')->insert([
			// 	'name'=>trim($user['name'])
			// 	,'password'=>password_hash($user['password'],PASSWORD_BCRYPT)
			// 	,'created' => date('Y-m-d H:i:s')
			// ]);

			// $uid = $this->db->id();

			// $this->db->from('usermeta')->multi_insert(array('uid','key','value')
			// 	,array(
			// 		array($uid,'is_admin', isset($user['is_admin']) ? $user['is_admin'] : 'false')
			// 		,array($uid,'email', isset($user['email']) ? $user['email'] : '')
			// 		,array($uid,'avatar', isset($user['avatar']) ? $user['avatar'] : '')
			// 		,array($uid,'description', isset($user['description']) ? $user['description'] : '')
			// 		,array($uid,'level', isset($user['level']) ? $user['level'] : '1')
			// 		,array($uid,'safetycode', isset($user['safetycode']) ? password_hash($user['safetycode'],PASSWORD_BCRYPT) : '')
			// 	)
			// );
		}
	}

	/* 获取用户详细 */
	public function getMeta($uid)
	{
		return $this->db->from('usermeta')->select("max(case `key` when 'is_admin' then `value` else 'false' end) is_admin,
 				max(case `key` when 'email' then `value` else '' end) email,
 				max(case `key` when 'avatar' then `value` else '' end) avatar,
 				max(case `key` when 'description' then `value` else '' end) description,
 				max(case `key` when 'level' then `value` else '' end) level,
 				max(case `key` when 'safetycode' then `value` else '' end) safetycode ")
			->where('`uid`=%d',$uid)
			->group('uid')->row();
	}

	/* 删除一个用户 */
	public function delete($uid)
	{

	}

	/* 检查用户名是否存在 */
	public function checkUsername($username)
	{
		return (bool) $this->exec("select count(*) from ~prefix~users where `name`=%s",$username)->query()->one();
	}

	/*  检查密码是否正确 */
	public function checkPassword($username, $password)
	{
		$pw = $this->db->from('users')->select('password')->where('`name`=%s',$username)->one();
		return password_verify($password, $pw);
	}

	/* 更新登录令牌 */
	public function updateToken($username, $days = 1)
	{
		$dbhash = G('database','hash');
		$timeout = date('Y-m-d H:i:s',strtotime("+{$days} day")); // 登录状态记录一天
		$data = [
			'logged' => date('Y-m-d H:i:s'),
			'timeout' => $timeout,
			'token' => md5( $dbhash . md5($username) . $timeout )
		];
		$this->db->from('users')->where('`name` = %s',$username)->update($data);
		return $data;
	}

	/* 检查登录令牌 */
	public function checkToken($loginToken)
	{
		$currentTime = date('Y-m-d H:i:s');
		$verifications = $this->db->from('users')->select('id,name,timeout')
			->where('`token`=%s',$loginToken)
			->row();

		if($verifications && $currentTime < $verifications['timeout']){
			return $verifications;
		}
		return false;
	}

	public function result($page=1, $limit=10)
	{
		return $this->db->from('users')->select('`id`,`name`,`created`,`logged`')
			->order('created DESC LIMIT '.(($page-1)*$limit).",".$limit)
			->rows();
	}
}