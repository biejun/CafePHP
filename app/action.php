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

	$allowAccess = false;
	// 验证当前临时会话窗口是否已存在登录记录
	if(is_null($this->session->get('login_uid'))){ // 不存在则检查浏览器中是否存在登录令牌
		$loginToken = $this->cookie->get('user_login_token'); // 取出令牌
		if(!is_null($loginToken)){
			// 检查令牌
			$tokenResult = $this->load('admin@users')->checkToken($loginToken);
			if($tokenResult){
				$allowAccess = true;
				// 如果令牌是正确的就将当前用户信息存到临时会话中
				$this->session->set('login_uid',$tokenResult['id']);
				$this->session->set('login_name',$tokenResult['name']);
				// 将当前已登录用户信息写进视图模型
				$this->view->account = new stdClass;
				$this->view->account->uid = $tokenResult['id'];
				$this->view->account->name = $tokenResult['name'];
			}
		}
	}else{
		$allowAccess = true;
		$this->view->account = new stdClass;
		$this->view->account->uid = $this->session->get('login_uid');
		$this->view->account->name = $this->session->get('login_name');
	}

	if(!$allowAccess){
		if(is_null($redirect)){
			if($this->request->isAjax()){
				$this->response->sendJSON('登录超时!',false);
			}else{
				$this->render('403',null,403);
			}
		}else{
			$this->response->redirect($redirect);
		}
	}
});