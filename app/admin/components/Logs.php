<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Logs extends Component
{
	public $data = array();

	public $loginLogs = 'admin:loginlogs';

	public $operateLogs = 'admin:operatelogs';

	/* 添加登录日志 */
	public function addLoginLog($username)
	{
		$data = $this->getLoginLogs()->all();

		$data[] = ['name'=>$username,'time'=>date("Y-m-d H:i"),'city'=> '火星'];

		$this->cache->set($this->loginLogs,$data);
	}

	/* 添加操作日志 */
	public function addOperateLog($username, $operateContent)
	{
		$data = $this->getOperateLogs()->all();

		$data[] = ['name'=>$username,'time'=>date("Y-m-d H:i"),'text'=> $operateContent];

		$this->cache->set($this->operateLogs,$data);
	}

	public function getLoginLogs()
	{
		$this->data = $this->cache->get($this->loginLogs);
		return $this;
	}

	public function getOperateLogs()
	{
		$this->data = $this->cache->get($this->operateLogs);
		return $this;
	}

	public function all()
	{
		if(!empty($this->data)){
			return array_reverse($this->data);
		}
		return array();
	}

	public function result($page = 1, $limit = 10)
	{
		if(!empty($this->data)){
			return array_reverse(array_slice($this->data, $limit * ($page - 1), $limit));
		}
		return array();
	}

	public function delete()
	{

	}
}