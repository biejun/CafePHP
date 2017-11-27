<?php
/* 路由中间件介绍 */

/* 路由器初始化时挂载的动作 */
$action->add('route:init',function(){

	/* 检查系统初始化安装配置 */
	if($this->checkSystemInstall())
	{
		/* 读取站点配置 */
		$options = $this->load('admin@options')->get();
		$this->view->options = new stdClass;
		while (list($key, $value) = each($options)) {
			$this->view->options->{$value['name']} = $value['value'];
		}
		/* 设置默认主题 */
		$this->view->setView('pc');
	}
	else
	{
		$php_sapi = PHP_SAPI;

		if($php_sapi === 'apache2handler')
		{
			urlRewriteByApache();
		}
		else if($php_sapi === 'fpm-fcgi' || $php_sapi === 'cgi-fcgi')
		{
			urlRewriteByNginx();
		}

		$this->view->setView('admin');
	}
});

/* 路由请求响应前挂载的动作 */
$action->add('route:before',function(){

});

/* 路由请求响应后挂载的动作 */
$action->add('route:after',function(){

});

/* 路由请求响应失败后挂载的动作 */
$action->add('route:failed',function(){

});

/* 检查登录 */
$action->add('check:login',function($redirect = null){
	$loginToken = $this->cookie->get('user_login_token');
	if(!is_null($loginToken)){
		if(!isset($this->session->login_id)){
			$tokenResult = $this->load('admin@users')->checkToken($loginToken);
			if($tokenResult){
				$this->session->login_id = $tokenResult['id'];
				$this->session->login_name = $tokenResult['name'];
			}else{
				if(is_null($redirect)){
					$this->response->sendJSON('您还没有登录!',false);
				}else{
					$this->response->redirect($redirect);
				}
			}
		}
	}else{
		if(is_null($redirect)){
			$this->response->sendJSON('您还没有登录!',false);
		}else{
			$this->response->redirect($redirect);
		}
	}
});